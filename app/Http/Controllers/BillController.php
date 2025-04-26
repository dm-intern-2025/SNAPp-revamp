<?php

namespace App\Http\Controllers;
use App\Services\OracleInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
    
}
