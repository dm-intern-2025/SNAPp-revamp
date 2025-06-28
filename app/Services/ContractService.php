<?php

namespace App\Services;

use App\Models\Contract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
        $disk = config('filesystems.default');

        // 1) New DB contracts
        $dbContracts = Contract::where('shortname', $short)->get();
        foreach ($dbContracts as $c) {
            $status = 'unknown';
            if ($c->contract_end) {
                $endDate = Carbon::parse($c->contract_end);
                $status = $endDate->isPast() ? 'Expired' : 'Active';
            }

            // Resolve document URL
            $url = $this->resolveFileUrl($c->document, $disk);

            $contracts[] = [
                'reference_number' => $c->reference_number,
                'contract_name'    => $c->description,
                'contract_period'  => $c->contract_period,
                'contract_end'     => $c->contract_end,
                'upload_date'      => $c->created_at->format('d-M-Y'),
                'status'           => $status,
                'gcsPdfUrl'        => $url,
            ];
        }

        // 2) Legacy single‐file contract
        $legacyPath = "snapp_contracts/CONTRACT_{$short}.pdf";
        $legacyUrl = $this->resolveFileUrl($legacyPath, $disk);

        if ($legacyUrl) {
            $contracts[] = [
                'reference_number' => null,
                'contract_name'    => "Legacy Contract for {$short}",
                'contract_period'  => null,
                'contract_end'     => null,
                'upload_date'      => Carbon::now()->format('d-M-Y'),
                'status'           => 'Available',
                'gcsPdfUrl'        => $legacyUrl,
            ];
        }

        return $contracts;
    }

    protected function resolveFileUrl(string $path, string $disk): ?string
    {
        if ($disk === 'gcs') {
            $url = $this->gcsService->generateSignedUrl($path);
            return $url ?: null;
        }

        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->url($path);
        }

        return null;
    }
}
