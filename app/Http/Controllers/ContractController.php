<?php
namespace App\Http\Controllers;

use App\Services\ContractService;

class ContractController extends Controller
{
    public function __construct(protected ContractService $contractService) {}

    public function showContractsPage()
    {
        $user = auth()->user();

        // Use the injected service from the constructor
        $contract = $this->contractService->getContractForUser($user);

        $contracts = $contract ? [$contract] : [];

        return view('my-contracts', compact('contracts'));
    }
}
