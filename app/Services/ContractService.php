<?php

// app/Services/ContractService.php

namespace App\Services;

use App\Models\Contract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\GcsService;

class ContractService
{
    protected GcsService $gcsService;

    public function __construct(GcsService $gcsService)
    {
        $this->gcsService = $gcsService;
    }

    public function getContractForUser($user): array
    {
        $short = $user->profile->short_name ?? null;
        if (!$short) {
            Log::warning("User {$user->id} has no short_name.");
            return [];
        }

        $contracts = [];

        // 1) New DB contracts
        $dbContracts = Contract::where('shortname', $short)->get();
        foreach ($dbContracts as $c) {
            $status = 'unknown';
            if ($c->contract_end) {
                $endDate = \Carbon\Carbon::parse($c->contract_end);
                $status = $endDate->isPast() ? 'Expired' : 'Active';
            }
            $contracts[] = [
                'reference_number' => $c->reference_number,
                'contract_name'    => $c->description,
                'contract_period'  => $c->contract_period,
                'contract_end'     => $c->contract_end,
                'upload_date'      => $c->created_at->format('d-M-Y'),
                'status'           => $status,
                'gcsPdfUrl'        => asset('storage/' . $c->document),
            ];
        }

        // 2) Legacy single‐file contract
        $legacyPath = "snapp_contracts/CONTRACT_{$short}.pdf";

        // Try generating a signed URL from GCS
        $url = $this->gcsService->generateSignedUrl($legacyPath);

        if ($url) {
            $contracts[] = [
                'reference_number' => null,
                'contract_name'    => "Legacy Contract for {$short}",
                'contract_period'  => null,
                'contract_end'     => null,
                'upload_date'      => null,
                'status'           => 'Available',
                'gcsPdfUrl'        => $url,
            ];
        }
        // Fallback to local storage if GCS file not found
        elseif (file_exists(storage_path('app/public/' . $legacyPath))) {
            $contracts[] = [
                'reference_number' => null,
                'contract_name'    => "Legacy Contract for {$short}",
                'contract_period'  => null,
                'contract_end'     => null,
                'upload_date'      => Carbon::now()->format('d-M-Y'),
                'status'           => 'Available',
                'gcsPdfUrl'        => asset('storage/' . $legacyPath),
            ];
        }


        return $contracts;
    }
}
