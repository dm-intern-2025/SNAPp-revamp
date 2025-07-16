<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BillingService
{
    public function __construct(
        protected OracleInvoiceService $oracleService,
        protected GcsService $gcsService
    ) {}


    public function getPaginatedUploadedBills(Request $request): LengthAwarePaginator
    {
        $disk = config('filesystems.default');

        $dbBills = Bill::with('profile')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $dbBills->getCollection()->transform(function (Bill $bill) use ($disk) {
            return [
                'billNumber'    => $bill->bill_number,
                'accountName'   => $bill->profile?->account_name ?? 'No Profile',
                'billingPeriod' => $bill->billing_period,
                'uploadedAt'    => $bill->created_at->format('d-M-Y'),
                'gcsPdfUrl' => $this->resolveFileUrl(
                    "snapp_bills/{$bill->profile?->short_name}_{$bill->billing_period}_{$bill->bill_number}.pdf",
                    $disk
                ),
            ];
        });

        return $dbBills;
    }




    public function getPaginatedBillsForUser($user, Request $request): LengthAwarePaginator
    {
        $customerId = $user->hasRole('admin') && $request->has('customer_id')
            ? $request->query('customer_id')
            : $user->customer_id;

        if (!$customerId) {
            session()->flash('info_message', 'No customer selected.');
            return $this->paginate(collect(), 5, $request, 'bills.show');
        }

        $profile = Profile::where('customer_id', $customerId)->first();
        if (!$profile) {
            session()->flash('info_message', 'Selected customer has no profile.');
            return $this->paginate(collect(), 5, $request, 'bills.show');
        }

        $customerShortname = $profile->short_name;

        // Fetch and format invoice data
        $rawOracleItems = $this->oracleService->fetchInvoiceData($customerId);
        $allBills = $this->prepareBillData(collect($rawOracleItems), $customerShortname);

        // Optional search filter
        if ($search = strtolower($request->input('search'))) {
            $allBills = $allBills->filter(
                fn($bill) =>
                str_contains(strtolower($bill['Billing Period']), $search) ||
                    str_contains(strtolower($bill['Power Bill Number']), $search)
            );
        }

        // Optional facility filter
        if ($facility = $request->input('facility')) {
            $allBills = $allBills->filter(
                fn($bill) => $bill['Facility'] === $facility
            );
        }

        return $this->paginate($allBills, 5, $request, 'bills.show');
    }


    protected function prepareBillData(Collection $items, ?string $customerShortname): Collection
    {
        return $items->map(function ($item) use ($customerShortname) {

            $billingPeriodForFile = null;

            if (isset($item['Comments'])) {
                // Split the string by " to "
                $parts = explode(' to ', $item['Comments']);

                // Check if we have two parts, as expected
                if (count($parts) === 2) {
                    // Uppercase each date part individually and join them back with a lowercase " to "
                    $billingPeriodForFile = strtoupper(trim($parts[0])) . ' to ' . strtoupper(trim($parts[1]));
                } else {
                    // Fallback for any unexpected format
                    $billingPeriodForFile = strtoupper($item['Comments']);
                }
            }

            $documentNumber = $item['DocumentNumber'] ?? ($item['TransactionNumber'] ?? null);
            $gcsPdfUrl = null;

            if ($customerShortname && $billingPeriodForFile && $documentNumber) {
                $objectPath = "snapp_bills/{$customerShortname}_{$billingPeriodForFile}_{$documentNumber}.pdf";
                $gcsPdfUrl = $this->gcsService->generateSignedUrl($objectPath);
            }

            return [
                'Facility'          => $item['SpecialInstructions'] ?? 'N/A',
                'Billing Period'    => $this->formatBillingRangeForDisplay($item['Comments'] ?? null),
                'Power Bill Number' => $item['DocumentNumber'] ?? '',
                'Posting Date'      => isset($item['TransactionDate']) ? \Carbon\Carbon::parse($item['TransactionDate'])->format('m/d/Y') : '',
                'Status'            => ($item['InvoiceBalanceAmount'] ?? 0) == 0 ? 'PAID' : 'UNPAID',
                'Total Amount'      => number_format($item['EnteredAmount'] ?? 0, 2),
                'gcsPdfUrl'         => $gcsPdfUrl,
            ];
        });
    }

    private function paginate(Collection $collection, int $perPage, Request $request, string $routeName): LengthAwarePaginator
    {
        $currentPage = $request->input('page', 1);
        $currentPageItems = $collection->forPage($currentPage, $perPage)->values();

        return new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => route($routeName), 'query' => $request->query()]
        );
    }

    private function formatBillingRangeForDisplay(?string $oracleComments): string
    {
        if (empty($oracleComments)) {
            return 'N/A';
        }

        try {
            // 1. Splits "26-Oct-22 to 25-Nov-22" into two parts
            $parts = explode(' to ', $oracleComments);
            if (count($parts) !== 2) {
                return $oracleComments; 
            }

            $startDate = Carbon::parse(trim($parts[0]));
            $endDate = Carbon::parse(trim($parts[1]));

            return $startDate->format('m/d/Y') . '-' . $endDate->format('m/d/y');
        } catch (\Exception $e) {
            // If parsing fails for any reason, log it and return the original string
            Log::warning("Could not format billing period display string: '{$oracleComments}'");
            return $oracleComments;
        }
    }


    public function getPaginatedPaymentHistoryForUser($user, Request $request): LengthAwarePaginator
    {
        $rawOracleItems = $this->oracleService->fetchInvoiceData($user->customer_id);

        $allPayments = collect($rawOracleItems)->map(function ($item) {
            return [
                'Payment Reference'      => $item['DocumentNumber'] ?? '',
                'Payment Reference Date' => isset($item['AccountingDate']) ? \Carbon\Carbon::parse($item['AccountingDate'])->format('m/d/Y') : '',
                'Billing Period'         => $item['Comments'] ?? 'N/A',
                'Amount'                 => number_format($item['EnteredAmount'] ?? 0, 2),
                'Power Bill No'          => $item['DocumentNumber'] ?? '',
                'Date Posted'            => isset($item['AccountingDate']) ? \Carbon\Carbon::parse($item['AccountingDate'])->format('m/d/Y') : '',
            ];
        });

        return $this->paginate($allPayments, 5, $request, 'payments.history');
    }
    protected function resolveFileUrl(?string $path, string $disk): ?string
    {
        if (is_null($path)) {
            return null;
        }

        if ($disk === 'gcs') {
            return $this->gcsService->generateSignedUrl($path);
        }

        return Storage::disk($disk)->url($path);
    }
}
