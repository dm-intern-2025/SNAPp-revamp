<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditCustomerRequest;
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
    public function showAdmins()
    {
        $admins = User::role('admin')->paginate(10);
        return view('admin.admin-account.admin-list', compact('admins'));
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
    public function edit($id)
    {
        // Retrieve the customer by ID
        $user = User::findOrFail($id);
    
        // Return a view with the customer data for editing
        return view('admin.customer-account.form-edit-customer', compact('user'));
    }
    

    /**
     * Update the specified resource in storage.
     */
public function update(EditCustomerRequest $request, User $user)
{
    $user->update($request->validated());
    return redirect()->route('users.index')->with('success', 'Customer updated.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true]);
    }
    
    
}
