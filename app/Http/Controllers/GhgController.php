<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OracleInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GhgController extends Controller
{
    protected $oracleInvoiceService;

    public function __construct(OracleInvoiceService $oracleInvoiceService)
    {
        $this->oracleInvoiceService = $oracleInvoiceService;
    }

    public function calculateEmissions()
    {
        $user = Auth::user();
        $customerId = $user->customer_id;

        // Fetch invoice data from the service
        $invoiceData = $this->oracleInvoiceService->fetchInvoiceData($customerId);

        if (empty($invoiceData)) {
            return response()->json(['error' => 'No invoices found for this customer'], 404);
        }

        // Assuming the first invoice is the one we're interested in (you may need to adjust this logic based on your actual data structure)
        $latestInvoice = $invoiceData[0];

        // Fetch consumption data for this invoice
        $transactionId = $latestInvoice['CustomerTransactionId'] ?? null;

        if (!$transactionId) {
            return response()->json(['error' => 'Invalid invoice data'], 400);
        }

        $transactionId = $latestInvoice['CustomerTransactionId'] ?? null;
        $consumption = 0;
    
        if ($transactionId) {
            $consumption = $this->oracleInvoiceService->fetchConsumption($transactionId); // No loop, just get the total
        }

        // Perform the GHG calculations
        $ERF = 0.709; // Emissions Reduction Factor (kg CO2 / kWh)
        $BulbReplacementFactor = 37.2; // kWh per bulb replaced
        $SequestrationRate = 60; // kg CO2 per tree grown
        $TrashBagConversionFactor = 23.1; // kg CO2 per trash bag recycled

        // 1. Avoided Emissions Calculation (kg CO2)
        $avoidedEmissions = $consumption * $ERF;

        // 2. Bulb Replacements Calculation (number of bulbs)
        $bulbReplacement = $consumption / $BulbReplacementFactor;

        // 3. Trees Grown Calculation (number of trees)
        $treesGrown = $avoidedEmissions / $SequestrationRate;

        // 4. Trash Bags Recycled Calculation (number of trash bags)
        $trashBagsRecycled = $avoidedEmissions / $TrashBagConversionFactor;

        // Return the data to the view, passing the dynamically calculated values
        return view('energy-consumption', [
            'consumption' => number_format($consumption, 2),
            'avoidedEmissions' => number_format($avoidedEmissions, 2),
            'bulbReplacement' => number_format($bulbReplacement, 2),
            'treesGrown' => number_format($treesGrown, 2),
            'trashBagsRecycled' => number_format($trashBagsRecycled, 2),
        ]);
    }
}
