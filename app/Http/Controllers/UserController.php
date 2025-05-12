<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditCustomerRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\StoreAccountExecutive;
use App\Http\Requests\StoreAdminRequest;
use Illuminate\Support\Str;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Requests\UpdateAERequest;
use App\Mail\CustomerPasswordMail;
use App\Models\Scopes\HasActiveScope;
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
        $validated = $request->validated();
        
        $user->update([
            'name' => $validated['edit_name'],
            'email' => $validated['edit_email'],
            'customer_id' => $validated['edit_customer_id']
        ]);
    
        return redirect()->
        route('users.index')->
        with('success', 'Customer updated.');
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

    public function updateAdmins(UpdateAdminRequest $request, User $user)
    {
        $validated = $request->validated();
        
        $user->update([
            'name' => $validated['edit_name'],
            'email' => $validated['edit_email'],
            'customer_id' => $validated['edit_customer_id']
        ]);        
        return redirect()
        ->route('admin-list')
        ->with('success', 'Admin updated.');

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

    public function updateAE(UpdateAERequest $request, User $user)
    {
        $validated = $request->validated();
        
        $user->update([
            'name' => $validated['edit_name'],
            'email' => $validated['edit_email'],
            'customer_id' => $validated['edit_customer_id']
        ]);        
        return redirect()
        ->route('admin-list')
        ->with('success', 'Admin updated.');

    }
   
/** ALL USERS */
public function showAllUsers(Request $request)
{
    $query = User::query()
                 ->withoutGlobalScope(HasActiveScope::class);

    // 1. Role filter (only if non‑empty)
    if ($request->filled('role')) {
        $query->whereHas('roles', function ($q) use ($request) {
            $q->where('name', $request->role);
        });
    }

    // 2. Active/inactive filter (only if non‑empty)
    if ($request->filled('active')) {
        $query->where('active', $request->active);
    }

    // 3. Search by name/email
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name',  'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // 4. Sorting: support name A–Z, name Z–A, newest, oldest
    $sort = $request->input('sort', 'created_at_desc');
    switch ($sort) {
        case 'name_asc':
            $query->orderBy('name', 'asc');
            break;
        case 'name_desc':
            $query->orderBy('name', 'desc');
            break;
        case 'created_at_asc':
            $query->orderBy('created_at', 'asc');
            break;
        case 'created_at_desc':
        default:
            $query->orderBy('created_at', 'desc');
            break;
    }

    // Paginate & preserve query string
    $allUsers = $query
        ->paginate(10)
        ->appends($request->only(['role','active','search','sort']));

    $roles = Role::all();

    return view('admin.all-users.all-users-list', compact('allUsers', 'roles'));
}




public function editAllUsers(EditUserRequest $request, $id)
{
    // dd($request->all());
    // Bypass global scope so even inactive users can be updated
    $user = User::withoutGlobalScope(HasActiveScope::class)->findOrFail($id);

    // Update only active status
    $user->active = (int) $request->input('active', 0);

    // Optionally update other validated fields (like name/email)
    $validated = $request->validated();
    $user->fill($validated)->save();

    // Update role if provided
    if ($request->filled('role')) {
        $user->syncRoles([$request->role]);
    }

    return redirect()->route('all-user-list')->with('success', 'User status updated.');
}

    
}
