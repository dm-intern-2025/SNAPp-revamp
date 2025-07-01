<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OracleInvoiceService;
use Illuminate\Support\Carbon;
use App\Models\Advisory;

class DashboardController extends Controller
{
    protected $oracleInvoiceService;

    public function __construct(OracleInvoiceService $oracleInvoiceService)
    {
        $this->oracleInvoiceService = $oracleInvoiceService;
    }

    public function showDashboardFields()
    {
        $user = Auth::user();
        $customerId = $user->customer_id;
        $accountName = $user->profile?->account_name;


        // Fetch invoice data
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
            $consumption = $this->oracleInvoiceService->fetchConsumption($transactionId);
        }

        // Fetch advisories
        $moreAdvisories = Advisory::latest()
            ->take(3)
            ->get();
        // Build in your controller:
        $upper = strtoupper($user->profile->account_name);
        $filterJson    = json_encode(['df50' => "include IN {$upper}"]);
        $encodedFilter = rawurlencode($filterJson);

        // NOTE the `/u/0/` here:
        $lookerUrl = "https://lookerstudio.google.com/embed/u/0/reporting/"
            . "4d1cf425-bcf4-4164-bf00-0e16b20bc79a/"
            . "page/p_n0steo0jnc"
            . "?params={$encodedFilter}";
            
        return view('dashboard', [
            'customerName'    => $user->name ?? 'Customer',
            'customerId'      => $customerId,
            'billingPeriod'   => $billingPeriod,
            'consumption'     => $consumption,
            'previousBalance' => number_format($previousBalance, 2),
            'currentAmount'   => number_format($currentAmount, 2),
            'dueDate'         => $dueDate,
            'moreAdvisories' => $moreAdvisories,
            'lookerUrl' => $lookerUrl,
        ]);
    }
    public function loadMore(Request $request)
    {
        return Advisory::latest()
            ->skip($request->skip ?? 0)
            ->take(3)
            ->get();
    }
}
