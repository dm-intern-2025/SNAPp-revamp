<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GhgController extends Controller
{
    public function calculateEmissions()
    {
        // Static monthly consumption value
        $consumption = 1419465.25;

        // Constants for calculations
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

        return view('my-ghg-emissions', [
            'consumption' => number_format($consumption, 2),
            'avoidedEmissions' => number_format($avoidedEmissions, 2),
            'bulbReplacement' => number_format($bulbReplacement, 2),
            'treesGrown' => number_format($treesGrown, 2),
            'trashBagsRecycled' => number_format($trashBagsRecycled, 2),
        ]);
    }
}
