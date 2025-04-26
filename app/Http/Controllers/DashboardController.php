<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OracleInvoiceService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    protected $oracleInvoiceService;

    // Inject the service into the controller
    public function __construct(OracleInvoiceService $oracleInvoiceService)
    {
        $this->oracleInvoiceService = $oracleInvoiceService;
    }

    public function showDashboardFields()
    {
        $user = Auth::user();
        $customerId = $user->customer_id;

        // Fetch invoice data from the service
        $items = $this->oracleInvoiceService->fetchInvoiceData($customerId);
        $latestInvoice = collect($items)->first();

        $billingPeriod = isset($latestInvoice['TransactionDate']) 
            ? Carbon::parse($latestInvoice['TransactionDate'])->format('F Y')
            : 'N/A';

        $dueDate = isset($latestInvoice['DueDate']) 
            ? Carbon::parse($latestInvoice['DueDate'])->format('m/d/Y')
            : 'N/A';

        $previousBalance = $latestInvoice['InvoiceBalanceAmount'] ?? 0;
        $currentAmount = $latestInvoice['EnteredAmount'] ?? 0;

        $transactionId = $latestInvoice['CustomerTransactionId'] ?? null;
        $consumption = 0;
    
        if ($transactionId) {
            $consumption = $this->oracleInvoiceService->fetchConsumption($transactionId); // No loop, just get the total
        }

        return view('dashboard', [
            'customerName'    => $user->name ?? 'Customer',
            'customerId'      => $customerId,
            'billingPeriod'   => $billingPeriod,
            'consumption'     => $consumption,
            'previousBalance' => number_format($previousBalance, 2),
            'currentAmount'   => number_format($currentAmount, 2),
            'dueDate'         => $dueDate
        ]);
    }
}
