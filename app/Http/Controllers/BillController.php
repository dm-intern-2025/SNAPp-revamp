<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BillController extends Controller
{
    private function apiHeaders()
    {
        return [
            'Content-Type' => 'application/vnd.oracle.adf.resourcecollection+json',
            'Accept'       => 'application/json',
        ];
    }

    private function apiAuth()
    {
        return [
            env('API_USERNAME'),
            env('API_PASSWORD'),
        ];
    }

    public function showBillsPage(Request $request)
    {
        $customerId  = Auth::user()->customer_id;
        $perPage     = 5;
        $currentPage = $request->input('page', 1);

        // 1) Fetch ALL invoices for this customer
        $response = Http::withBasicAuth(...$this->apiAuth())
            ->withHeaders($this->apiHeaders())
            ->get('https://fa-evjn-dev1-saasfaprod1.fa.ocs.oraclecloud.com/fscmRestApi/resources/11.13.18.05/receivablesInvoices', [
                'finder' => "invoiceSearch;
                    TransactionSource=SNAP AUTOINVOICE,
                    TransactionType=TRADE-RES,
                    BusinessUnit=SNAPR BU,
                    BillToCustomerNumber={$customerId}",
            ]);

        if (! $response->successful()) {
            return back()->with('error', 'Failed to fetch bills');
        }

        // 2) Map into the shape you need
        $allBills = collect($response->json()['items'] ?? [])
            ->map(function ($item) {
                return [
                    'Billing Period'     => $item['Comments'] ?? 'N/A',
                    'Power Bill Number'  => $item['DocumentNumber'] ?? '',
                    'Bill Date'          => Carbon::parse($item['TransactionDate'])->format('m/d/Y'),
                    'Terms'              => $item['PaymentTerms'] ?? '',
                    'Due Date'           => Carbon::parse($item['DueDate'])->format('m/d/Y'),
                    'Total Amount'       => number_format($item['EnteredAmount'] ?? 0, 2),
                    'Status'             => ($item['InvoiceBalanceAmount'] == 0) ? 'PAID' : 'UNPAID',
                ];
            });

        // 3) Compute totals & slice current page
        $totalItems       = $allBills->count();
        $currentPageItems = $allBills
            ->forPage($currentPage, $perPage)   // equivalent to slice(($currentPage-1)*$perPage, $perPage)
            ->values();                         // re-index to [0..n]

        // 4) Build the paginator
        $paginator = new LengthAwarePaginator(
            $currentPageItems,
            $totalItems,
            $perPage,
            $currentPage,
            ['path' => route('bills.show')]
        );

        return view('my-bills', [
            'bills'     => $paginator,
            'payments'  => null,
            'activeTab' => 'bills',
        ]);
    }

    public function showPaymentHistory(Request $request)
    {
        $customerId  = Auth::user()->customer_id;
        $perPage     = 5;
        $currentPage = $request->input('page', 1);

        $response = Http::withBasicAuth(...$this->apiAuth())
            ->withHeaders($this->apiHeaders())
            ->get('https://fa-evjn-dev1-saasfaprod1.fa.ocs.oraclecloud.com/fscmRestApi/resources/11.13.18.05/receivablesInvoices', [
                'finder' => "invoiceSearch;
                    TransactionSource=SNAP AUTOINVOICE,
                    TransactionType=TRADE-RES,
                    BusinessUnit=SNAPR BU,
                    BillToCustomerNumber={$customerId}",
            ]);

        if (! $response->successful()) {
            return back()->with('error', 'Failed to fetch payments');
        }

        $allPayments = collect($response->json()['items'] ?? [])
            ->map(function ($item) {
                return [
                    'Payment Reference'       => $item['DocumentNumber'] ?? '',
                    'Payment Reference Date'  => Carbon::parse($item['AccountingDate'])->format('m/d/Y'),
                    'Billing Period'          => $item['Comments'] ?? 'N/A',
                    'Amount'                  => number_format($item['EnteredAmount'] ?? 0, 2),
                    'Power Bill No'           => $item['DocumentNumber'] ?? '',
                    'Date Posted'             => Carbon::parse($item['AccountingDate'])->format('m/d/Y'),
                ];
            });

        $totalItems       = $allPayments->count();
        $currentPageItems = $allPayments
            ->forPage($currentPage, $perPage)
            ->values();

        $paginator = new LengthAwarePaginator(
            $currentPageItems,
            $totalItems,
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
