<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
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

   public function showDashboardFields()
{
    $user = Auth::user();
    $customerId = $user->customer_id;

    // Step 1: Fetch invoice using customerId
    $response = Http::withBasicAuth(...$this->apiAuth())
        ->withHeaders($this->apiHeaders())
        ->get('https://fa-evjn-dev1-saasfaprod1.fa.ocs.oraclecloud.com/fscmRestApi/resources/11.13.18.05/receivablesInvoices', [
            'finder' => "invoiceSearch;
                TransactionSource=SNAP AUTOINVOICE,
                TransactionType=TRADE-RES,
                BusinessUnit=SNAPR BU,
                BillToCustomerNumber={$customerId}"
        ]);

    $items = $response->successful() ? $response->json()['items'] ?? [] : [];
    $latestInvoice = collect($items)->first();

    $billingPeriod = isset($latestInvoice['TransactionDate']) 
        ? Carbon::parse($latestInvoice['TransactionDate'])->format('F Y')
        : 'N/A';

    $dueDate = isset($latestInvoice['DueDate']) 
        ? Carbon::parse($latestInvoice['DueDate'])->format('m/d/Y')
        : 'N/A';

    $previousBalance = $latestInvoice['InvoiceBalanceAmount'] ?? 0;
    $currentAmount = $latestInvoice['EnteredAmount'] ?? 0;

    // Step 2: Fetch line items using CustomerTransactionId
    $consumption = 0;
    $transactionId = $latestInvoice['CustomerTransactionId'] ?? null;

    if ($transactionId) {
        $lineResponse = Http::withBasicAuth(...$this->apiAuth())
            ->withHeaders($this->apiHeaders())
            ->get("https://fa-evjn-dev1-saasfaprod1.fa.ocs.oraclecloud.com/fscmRestApi/resources/11.13.18.05/receivablesInvoices/{$transactionId}/child/receivablesInvoiceLines");

        $lineItems = $lineResponse->successful() ? $lineResponse->json()['items'] ?? [] : [];

        foreach ($lineItems as $line) {
            $consumption = $lineItems[0]['Quantity'] ?? 0;

        }
    }

    return view('dashboard', [
        'customerName'    => $user->name ?? 'Customer',
        'customerId'      => $customerId,
        'billingPeriod'   => $billingPeriod,
        'consumption'     => number_format($consumption, 2),
        'previousBalance' => number_format($previousBalance, 2),
        'currentAmount'   => number_format($currentAmount, 2),
        'dueDate'         => $dueDate
    ]);
}

}