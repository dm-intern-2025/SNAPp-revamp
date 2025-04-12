<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class BillController extends Controller
{
    public function showBillsPage(Request $request)
    {
        // Get the dynamic customer ID (you can use $request->customer_id or a similar approach)
        $customerId = $request->input('customer_id', 2163);  // Default to 2163 if no ID is passed
    
        $response = Http::withBasicAuth(env('API_USERNAME'), env('API_PASSWORD'))
            ->withHeaders([
                'Content-Type' => 'application/vnd.oracle.adf.resourcecollection+json',
            ])
            ->get('https://fa-evjn-dev1-saasfaprod1.fa.ocs.oraclecloud.com/fscmRestApi/resources/11.13.18.05/receivablesInvoices', [
                'finder' => "invoiceSearch;TransactionSource=SNAP AUTOINVOICE,TransactionType=TRADE-RES,BusinessUnit=SNAPR BU,BillToCustomerNumber={$customerId}",
            ]);
    
        // Handle response
        dd($response->json());
    }
    
}
