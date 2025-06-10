<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class BillingService
{
    public function __construct(
        protected OracleInvoiceService $oracleService,
        protected GcsService $gcsService
    ) {
    }

    public function getPaginatedBillsForUser($user, Request $request): LengthAwarePaginator
    {
        $customerShortname = $user->profile->short_name ?? null;
        if (empty($customerShortname)) {
            session()->flash('info_message', 'Please complete your customer profile to enable viewing of bill PDFs.');
        }
        
        $rawOracleItems = $this->oracleService->fetchInvoiceData($user->customer_id);
        $allBills = $this->prepareBillData(collect($rawOracleItems), $customerShortname);

        $search = $request->input('search');
        if (!empty($search)) {
            $search = strtolower($search);
            $allBills = $allBills->filter(fn ($bill) => 
                str_contains(strtolower($bill['Billing Period']), $search) ||
                str_contains(strtolower($bill['Power Bill Number']), $search)
            );
        }

        return $this->paginate($allBills, 5, $request, 'bills.show');
    }

    protected function prepareBillData(Collection $items, ?string $customerShortname): Collection
    {
        return $items->map(function ($item) use ($customerShortname) {
            
            // This logic is for the GCS Filename
            $billingPeriodForFile = isset($item['Comments']) ? strtoupper($item['Comments']) : null;
            $documentNumber = $item['DocumentNumber'] ?? ($item['TransactionNumber'] ?? null);
            $gcsPdfUrl = null;

            if ($customerShortname && $billingPeriodForFile && $documentNumber) {
                $objectPath = "snapp_bills/{$customerShortname}_{$billingPeriodForFile}_{$documentNumber}.pdf";
                $gcsPdfUrl = $this->gcsService->generateSignedUrl($objectPath);
            }

            // This is the final data array for the view
            return [
                // --- THIS LINE IS NOW CHANGED TO USE THE NEW FORMATTING METHOD ---
                'Billing Period'    => $this->formatBillingRangeForDisplay($item['Comments'] ?? null),
                
                'Power Bill Number' => $item['DocumentNumber'] ?? '',
                'Posting Date'         => isset($item['TransactionDate']) ? Carbon::parse($item['TransactionDate'])->format('m/d/Y') : '',
                'Terms'             => $item['PaymentTerms'] ?? '',
                'Due Date'          => isset($item['DueDate']) ? Carbon::parse($item['DueDate'])->format('m/d/Y') : '',
                'Total Amount'      => number_format($item['EnteredAmount'] ?? 0, 2),
                'Status'            => ($item['InvoiceBalanceAmount'] ?? 0) == 0 ? 'PAID' : 'UNPAID',
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

    /**
     * This is the new helper method that formats the date range for display.
     */
    private function formatBillingRangeForDisplay(?string $oracleComments): string
    {
        if (empty($oracleComments)) {
            return 'N/A';
        }

        try {
            // 1. Splits "26-Oct-22 to 25-Nov-22" into two parts
            $parts = explode(' to ', $oracleComments);
            if (count($parts) !== 2) {
                return $oracleComments; // Return original if format is unexpected
            }

            // 2. Carbon::parse() reads each part and understands it
            $startDate = Carbon::parse(trim($parts[0]));
            $endDate = Carbon::parse(trim($parts[1]));

            // 3. Format each date into the style you want and join them
            //    $startDate->format('m/d/Y') produces "10/26/2022" (mm/dd/yyyy)
            //    $endDate->format('m/d/y')   produces "11/25/22" (mm/dd/yy)
            return $startDate->format('m/d/Y') . '-' . $endDate->format('m/d/y');

        } catch (\Exception $e) {
            // If parsing fails for any reason, log it and return the original string
            Log::warning("Could not format billing period display string: '{$oracleComments}'");
            return $oracleComments;
        }
    }

    // In app/Services/BillingService.php

    public function getPaginatedPaymentHistoryForUser($user, Request $request): LengthAwarePaginator
    {
        // 1. Fetch raw data from the Oracle Service
        $rawOracleItems = $this->oracleService->fetchInvoiceData($user->customer_id);

        // 2. Map the raw data into the "Payment History" format
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

        // 3. Paginate the final collection using our reusable helper method
        return $this->paginate($allPayments, 5, $request, 'payments.history');
    }
}