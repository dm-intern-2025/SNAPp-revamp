<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ContractService
{
    public function __construct(protected GcsService $gcsService) {}

    /**
     * Finds a single contract for a user based on their shortname.
     */
    public function getContractForUser($user): ?array
    {
        $short = $user->profile->short_name ?? null;
        if (!$short) {
            Log::warning("User {$user->id} has no short_name.");
            return null;
        }

        $objectPath = "snapp_contracts/CONTRACT_{$short}.pdf";



        $url = $this->gcsService->generateSignedUrl($objectPath);

        if ($url) {
            return [
                'contract_name' => "Service Contract for {$short}",
                'filename'      => $objectPath,
                'gcsPdfUrl'     => $url,
                'status'        => 'Available',
            ];
        }

        return null;
    }
}
