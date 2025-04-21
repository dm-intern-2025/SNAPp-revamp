<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Http\Requests\StoreCustomerRequest;
use App\Mail\CustomerPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::role('customer')->paginate(10);

        return view('admin.customer-account.customer-list',
            compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $password = Str::random(8);

        $validatedRequest = $request->validated();

        $validatedRequest['password'] = bcrypt($password);
        
        $user = User::create($validatedRequest);

        $user->assignRole('customer');

        // Mail::to($user->email)->send(new CustomerPasswordMail($password));

        return redirect()->back()->with('success', 'Customer account created successfully.');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
