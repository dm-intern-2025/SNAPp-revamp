<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditCustomerRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\StoreAccountExecutive;
use App\Http\Requests\StoreAdminRequest;
use Illuminate\Support\Str;
use App\Http\Requests\StoreCustomerRequest;
use App\Mail\CustomerPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::role('customer')->paginate(10);

        return view(
            'admin.customer-account.customer-list',
            compact('users')
        );
    }

    public function store(StoreCustomerRequest $request)
    {
        $password = Str::random(8);

        $validatedRequest = $request->validated();

        $validatedRequest['password'] = bcrypt($password);

        $user = User::create($validatedRequest);

        $user->assignRole('customer');

        Mail::to($user->email)->send(new CustomerPasswordMail($password));

        return redirect()->back()->with('success', 'Customer account created successfully.');
    }

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

    /** ADMIN ACCCOUNTS  */
    public function showAdmins()
    {
        $admins = User::role('admin')->paginate(10);
        return view('admin.admin-account.admin-list', compact('admins'));
    }

    public function storeAdmins(StoreAdminRequest $request)
    {
        $password = Str::random(8);

        $validatedRequest = $request->validated();

        $validatedRequest['password'] = bcrypt($password);

        $user = User::create($validatedRequest);

        $user->assignRole('admin');

        Mail::to($user->email)->send(new CustomerPasswordMail($password));

        return redirect()->back()->with('success', 'Customer account created successfully.');
    }

    /** AE ACCCOUNTS  */

    public function showAE()
    {
        $accountExecutives = User::role('account executive')->paginate(10);
        return view('admin.account-executive.account-executive-list', compact('accountExecutives'));
    }
    public function storeAE(StoreAccountExecutive $request)
    {
        $password = Str::random(8);

        $validatedRequest = $request->validated();

        $validatedRequest['password'] = bcrypt($password);

        $user = User::create($validatedRequest);

        $user->assignRole('account executive');

        Mail::to($user->email)->send(new CustomerPasswordMail($password));

        return redirect()->back()->with('success', 'Customer account created successfully.');
    }


    /** ALL USERS  */
    public function showAllUsers()
    {
        $allUsers = User::paginate(10);
        $roles = Role::all(); // available roles
        return view('admin.all-users.all-users-list', compact('allUsers', 'roles'));
    }

    public function editAllUsers(EditUserRequest $request, User $user)
    {
        $user->update($request->validated());
        $user->syncRoles([$request->role]);

        return redirect()->route('all-user-list');
    }
}
