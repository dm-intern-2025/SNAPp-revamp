<?php

namespace App\Http\Controllers;

use App\Services\OracleInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
// use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection as SupportCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BillController extends Controller
{
    protected $oracleInvoiceService;

    public function __construct(OracleInvoiceService $oracleInvoiceService)
    {
        $this->oracleInvoiceService = $oracleInvoiceService;
    }

    public function showBillsPage(Request $request)
    {
        $user       = Auth::user();
        $customerId = $user->customer_id;
        $perPage    = 5;
        $currentPage = $request->input('page', 1);

        // 1) Fetch from service
        $items = $this->oracleInvoiceService->fetchInvoiceData($customerId);

        if (empty($items)) {
            return back()->with('error', 'Failed to fetch bills.');
        }

        $latestInvoice = collect($items)->first(); // optional if used elsewhere

        // 2) Map into the shape you need
        $allBills = collect($items)->map(function ($item) {
            return [
                'Billing Period'     => $item['Comments'] ?? 'N/A',
                'Power Bill Number'  => $item['DocumentNumber'] ?? '',
                'Bill Date'          => isset($item['TransactionDate']) ? Carbon::parse($item['TransactionDate'])->format('m/d/Y') : '',
                'Terms'              => $item['PaymentTerms'] ?? '',
                'Due Date'           => isset($item['DueDate']) ? Carbon::parse($item['DueDate'])->format('m/d/Y') : '',
                'Total Amount'       => number_format($item['EnteredAmount'] ?? 0, 2),
                'Status'             => ($item['InvoiceBalanceAmount'] ?? 0) == 0 ? 'PAID' : 'UNPAID',
            ];
        });

        // 3) Paginate
        $totalItems       = $allBills->count();
        $currentPageItems = $allBills
            ->forPage($currentPage, $perPage)
            ->values(); // reset indexes

        $paginator = new LengthAwarePaginator(
            $currentPageItems,
            $totalItems,
            $perPage,
            $currentPage,
            ['path' => route('bills.show')]
        );

        // 4) Return view
        return view('my-bills', [
            'bills'     => $paginator,
            'payments'  => null,
            'activeTab' => 'bills',
        ]);
    }

    public function showPaymentHistory(Request $request)
    {
        $user        = Auth::user();
        $customerId  = $user->customer_id;
        $perPage     = 5;
        $currentPage = $request->input('page', 1);

        $items = $this->oracleInvoiceService->fetchInvoiceData($customerId);

        if (empty($items)) {
            return back()->with('error', 'Failed to fetch payments.');
        }

        $allPayments = collect($items)->map(function ($item) {
            return [
                'Payment Reference'       => $item['DocumentNumber'] ?? '',
                'Payment Reference Date'  => isset($item['AccountingDate']) ? Carbon::parse($item['AccountingDate'])->format('m/d/Y') : '',
                'Billing Period'          => $item['Comments'] ?? 'N/A',
                'Amount'                  => number_format($item['EnteredAmount'] ?? 0, 2),
                'Power Bill No'           => $item['DocumentNumber'] ?? '',
                'Date Posted'             => isset($item['AccountingDate']) ? Carbon::parse($item['AccountingDate'])->format('m/d/Y') : '',
            ];
        });

        $paginator = new LengthAwarePaginator(
            $allPayments->forPage($currentPage, $perPage)->values(),
            $allPayments->count(),
            $perPage,
            $currentPage,
            ['path' => route('payments.history')]
        );

        return view('my-bills', [
            'payments'  => $paginator,
            'bills'     => null,
            'activeTab' => 'payments',
        ]);
    }


    // EXPORT METHODS

    public function exportBills(Request $request)
    {
        $user       = Auth::user();
        $customerId = $user->customer_id;

        $items = $this->oracleInvoiceService->fetchInvoiceData($customerId);

        if (empty($items)) {
            return back()->with('error', 'No bills found to export.');
        }

        $allBills = collect($items)->map(function ($item) {
            return [
                'Billing Period'     => $item['Comments'] ?? 'N/A',
                'Power Bill Number'  => $item['DocumentNumber'] ?? '',
                'Bill Date'          => isset($item['TransactionDate']) ? Carbon::parse($item['TransactionDate'])->format('m/d/Y') : '',
                'Terms'              => $item['PaymentTerms'] ?? '',
                'Due Date'           => isset($item['DueDate']) ? Carbon::parse($item['DueDate'])->format('m/d/Y') : '',
                'Total Amount'       => number_format($item['EnteredAmount'] ?? 0, 2),
                'Status'             => ($item['InvoiceBalanceAmount'] ?? 0) == 0 ? 'PAID' : 'UNPAID',
            ];
        });

        return $this->exportToCsv($allBills, 'bills.csv');
    }

    public function exportPayments(Request $request)
    {
        $user       = Auth::user();
        $customerId = $user->customer_id;

        $items = $this->oracleInvoiceService->fetchInvoiceData($customerId);

        if (empty($items)) {
            return back()->with('error', 'No payments found to export.');
        }

        $allPayments = collect($items)->map(function ($item) {
            return [
                'Payment Reference'       => $item['DocumentNumber'] ?? '',
                'Payment Reference Date'  => isset($item['AccountingDate']) ? Carbon::parse($item['AccountingDate'])->format('m/d/Y') : '',
                'Billing Period'          => $item['Comments'] ?? 'N/A',
                'Amount'                  => number_format($item['EnteredAmount'] ?? 0, 2),
                'Power Bill No'           => $item['DocumentNumber'] ?? '',
                'Date Posted'             => isset($item['AccountingDate']) ? Carbon::parse($item['AccountingDate'])->format('m/d/Y') : '',
            ];
        });

        return $this->exportToCsv($allPayments, 'payments.csv');
    }

    protected function exportToCsv(SupportCollection $data, string $filename): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $columns = array_keys($data->first() ?? []);

        return new StreamedResponse(function () use ($data, $columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 200, $headers);
    }
}

