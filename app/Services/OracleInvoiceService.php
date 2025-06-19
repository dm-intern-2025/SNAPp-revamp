<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OracleInvoiceService
{
    private $apiHeaders;
    private $apiAuth;
    private $baseUrl;

    public function __construct()
    {
        $this->apiHeaders = [
            'Content-Type' => 'application/vnd.oracle.adf.resourcecollection+json',
            'Accept' => 'application/json',
        ];

        // BEST PRACTICE: Use the config() helper, not env()
        $this->apiAuth = [
            config('services.oracle.username'),
            config('services.oracle.password'),
        ];
        
        $this->baseUrl = config('services.oracle.url');

        // You can add a check to fail early if credentials are not set
        if (empty($this->apiAuth[0]) || empty($this->apiAuth[1])) {
            // In a real app, you'd throw a specific exception here.
            throw new \Exception('Oracle API credentials are not configured.');
        }
    }

    public function fetchInvoiceData($customerId)
    {
        // Use the base URL from the config
        $url = $this->baseUrl . 'receivablesInvoices';

        $queryParams = [
            'finder' => "invoiceSearch;TransactionSource=SNAP AUTOINVOICE,TransactionType=TRADE-RES,BusinessUnit=SNAPR BU,BillToCustomerNumber={$customerId}",
            'totalResults' => 'true'
        ];

        $response = Http::withBasicAuth(...$this->apiAuth)
            ->withHeaders($this->apiHeaders)
            ->get($url, $queryParams);

        return $response->successful() ? $response->json()['items'] ?? [] : [];
    }

    public function fetchConsumption($transactionId)
    {
        // Use the base URL from the config
        $url = "{$this->baseUrl}receivablesInvoices/{$transactionId}/child/receivablesInvoiceLines";
    
        $response = Http::withBasicAuth(...$this->apiAuth)
            ->withHeaders($this->apiHeaders)
            ->get($url);
    
        if ($response->successful()) {
            $items = $response->json()['items'] ?? [];
            return $items[0]['Quantity'] ?? 0;
        }
    
        return 0;
    }
}