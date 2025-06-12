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
use App\Models\Profile;
use App\Models\Scopes\HasActiveScope;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
   public function index(Request $request)
{
    // Start the query and scope it
    $query = User::query()
        ->role('customer');

    // **THE FIX: Eager-load the profile data right away.**
    $query->with('profile');

    // --- The rest of your function remains exactly the same ---

    // 1. Active/inactive filter
    if ($request->filled('active')) {
        $query->where('active', $request->active);
    }

    // 2. Search by name or email
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // 3. Sorting
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

    // 4. Pagination
    $users = $query
        ->paginate(10)
        ->appends($request->only(['active', 'search', 'sort']));

    return view('admin.customer-account.customer-list', compact('users'));
}


    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();
        $password = Str::random(8);

        DB::beginTransaction();
        try {
            // 1. Create the User (from your original code)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'customer_id' => $validated['customer_id'],
                'password' => bcrypt($password),
            ]);
            $user->assignRole('customer');

            // 2. Check if any optional profile data was submitted
            $profileData = $request->only([
                'account_name',
                'short_name',
                'customer_category',
                'contract_price',
                'contracted_demand',
                'cooperation_period_start_date',
                'cooperation_period_end_date'
            ]);

            // Only proceed if at least one profile field was filled
            if (count(array_filter($profileData)) > 0) {
                // 3. Use the firstOrNew logic from your ProfileController
                $profile = Profile::firstOrNew(['customer_id' => $user->customer_id]);
                $profile->fill($profileData);
                $profile->save();
            }

            Mail::to($user->email)->send(new CustomerPasswordMail($password));
            DB::commit();

            return redirect()->back()->with('success', 'Customer account created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer Creation Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create customer account.');
        }
    }

    /**
     * Corrected update method.
     */
    public function update(EditCustomerRequest $request, User $user)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // 1. Update the User (from your original code)
            $user->update([
                'name' => $validated['edit_name'],
                'email' => $validated['edit_email'],
                'customer_id' => $validated['edit_customer_id'],
            ]);

            // 2. Manually map the 'edit_' prefixed fields to the profile model
            $profileData = [
                'account_name' => $validated['edit_account_name'] ?? null,
                'short_name' => $validated['edit_short_name'] ?? null,
                'customer_category' => $validated['edit_customer_category'] ?? null,
                'contract_price' => $validated['edit_contract_price'] ?? null,
                'contracted_demand' => $validated['edit_contracted_demand'] ?? null,
                'cooperation_period_start_date' => $validated['edit_cooperation_period_start_date'] ?? null,
                'cooperation_period_end_date' => $validated['edit_cooperation_period_end_date'] ?? null,
            ];

            // 3. Filter out empty fields so we don't overwrite existing data with nulls
            $filteredProfileData = array_filter($profileData, fn($value) => !is_null($value));

            // 4. If there's data to update, use the firstOrNew logic
            if (count($filteredProfileData) > 0) {
                $profile = Profile::firstOrNew(['customer_id' => $user->customer_id]);
                $profile->fill($filteredProfileData);
                $profile->save();
            }

            DB::commit();
            return redirect()->route('users.index')->with('success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer Update Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update customer.');
        }
    }


    public function edit($id)
    {
        // Retrieve the customer by ID
        $user = User::findOrFail($id);

        // Return a view with the customer data for editing
        return view('admin.customer-account.form-edit-customer', compact('user'));
    }

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

    public function showAE(Request $request)
    {
        $query = User::query()
            ->role('account executive');


        // 1. Active/inactive filter (only if non-empty)
        if ($request->filled('active')) {
            $query->where('active', $request->active);
        }

        // 2. Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. Sorting
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

        // 4. Pagination with query string
        $accountExecutives = $query
            ->paginate(10)
            ->appends($request->only(['active', 'search', 'sort']));

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
            ->appends($request->only(['role', 'active', 'search', 'sort']));

        $roles = Role::all();

        return view('admin.all-users.all-users-list', compact('allUsers', 'roles'));
    }




    public function editAllUsers(EditUserRequest $request, $id)
    {
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
