<?php

namespace App\Http\Controllers;

use App\Models\AccountExecutive;
use Illuminate\Http\Request;

class AccountExecutiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.account-executive.account-executive-list');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountExecutive $accountExecutive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountExecutive $accountExecutive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountExecutive $accountExecutive)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountExecutive $accountExecutive)
    {
        //
    }
}
