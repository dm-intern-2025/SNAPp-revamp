<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdvisoryRequest;
use App\Models\Advisory;
use Illuminate\Http\Request;

class AdvisoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.advisory-management.advisory-list');
    }

    public function adminList()
    {
        $advisories = Advisory::orderBy('created_at', 'desc')->paginate(10); // Changed
        return view('admin.advisory-management.advisory-list', compact('advisories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdvisoryRequest $request)
    {
        $validatedRequest = $request->validated();
        Advisory::create($validatedRequest);


        return redirect()->back()->with('success', 'Customer account created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Advisory $advisory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Advisory $advisory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Advisory $advisory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advisory $advisory)
    {
        //
    }
}
