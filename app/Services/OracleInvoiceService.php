<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OracleInvoiceService
{
    private $apiHeaders;
    private $apiAuth;

    public function __construct()
    {
        $this->apiHeaders = [
            'Content-Type' => 'application/vnd.oracle.adf.resourcecollection+json',
            'Accept' => 'application/json',
        ];

        $this->apiAuth = [
            env('API_USERNAME'),
            env('API_PASSWORD'),
        ];
    }

// In app/Services/OracleInvoiceService.php

public function fetchInvoiceData($customerId)
{
    $url = 'https://fa-evjn-dev1-saasfaprod1.fa.ocs.oraclecloud.com/fscmRestApi/resources/11.13.18.05/receivablesInvoices';

    // We need to ask the API for the total count of results
    $queryParams = [
        'finder' => "invoiceSearch;TransactionSource=SNAP AUTOINVOICE,TransactionType=TRADE-RES,BusinessUnit=SNAPR BU,BillToCustomerNumber={$customerId}",
        'totalResults' => 'true' // Ask Oracle to tell us the total
    ];

    $response = Http::withBasicAuth(...$this->apiAuth)
        ->withHeaders($this->apiHeaders)
        ->get($url, $queryParams);

    return $response->successful() ? $response->json()['items'] ?? [] : [];
}

    public function fetchConsumption($transactionId)
    {
        $url = "https://fa-evjn-dev1-saasfaprod1.fa.ocs.oraclecloud.com/fscmRestApi/resources/11.13.18.05/receivablesInvoices/{$transactionId}/child/receivablesInvoiceLines";
    
        // Make the API call
        $response = Http::withBasicAuth(...$this->apiAuth)
            ->withHeaders($this->apiHeaders)
            ->get($url);
    
        // If the response is successful and contains items, return the first item quantity or 0
        if ($response->successful()) {
            $items = $response->json()['items'] ?? [];
    
            // Return the Quantity of the first item, or 0 if no item is found
            return isset($items[0]['Quantity']) ? $items[0]['Quantity'] : 0;
        }
    
        return 0; // Return 0 if the response is not successful or no items are found
    }
}    

