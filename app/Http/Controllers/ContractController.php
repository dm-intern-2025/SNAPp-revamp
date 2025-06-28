<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use App\Models\Profile;
use App\Services\ContractService;
use Illuminate\Support\Carbon;

class ContractController extends Controller
{
    public function __construct(protected ContractService $contractService) {}

    public function showContractsPage()
    {
        $user = auth()->user();

        $contracts = $this->contractService->getContractForUser($user);


        return view('my-contracts', compact('contracts'));
    }

    public function store(StoreContractRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        // pull shortname
        $profile = Profile::where('customer_id', $user->customer_id)->first();
        $short = $profile ? $profile->short_name : null;
        $validated['shortname'] = $short;

        // format period
        $start  = Carbon::parse($validated['contract_start']);
        $end    = Carbon::parse($validated['contract_end']);
        $period = strtoupper($start->format('d-M-y') . ' to ' . $end->format('d-M-y'));
        $validated['contract_period'] = $period;

        // reference number
        $ref = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        $validated['reference_number'] = $ref;

        $validated['status'] = 1;

        // **Grab extension first!**
        $extension = $request->file('document')->getClientOriginalExtension();

        // build custom filename
        $filename = "CONTRACT_{$short}_{$period}_{$ref}.{$extension}";

        // store under your name
        $path = $request->file('document')->storeAs('snapp_contracts', $filename, config('filesystems.default'));
        $validated['document'] = $path;

        Contract::create($validated);

        return redirect()->route('my-contracts')->with('success', 'Contract uploaded.');
    }
}
