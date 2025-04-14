<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BillController extends Controller
{
    public function showBillsPage(Request $request)
    {

        $customerId = Auth::user()->customer_id;
    
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
