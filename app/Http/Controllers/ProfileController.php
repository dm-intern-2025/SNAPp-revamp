<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $profiles = null;

        if ($user && $user->customer_id) {
            $profiles = Profile::where('customer_id', $user->customer_id)->first();
        }

        return view('profile', compact('profiles'));
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
    public function store(StoreProfileRequest $request)
    {
        $customerId = Auth::user()->customer_id;

        $profile = Profile::where('customer_id')->first();

        if ($profile) {
            $profile->update($request->validated());
            return back();
        }

        Profile::create(array_merge(
            $request->validated(),
            ['customer_id' => $customerId]
        ));

        return back()->with('success', 'Customer account created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $customerId = Auth::user()->customer_id;
        $profile = Profile::where('customer_id', $customerId)->first();

        if (!$profile) {
            $profile = new Profile(['customer_id' => $customerId]);
        }

        return view('edit-profile', compact('profile'));
    }
    
    
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        // Verify the user owns this profile
        if (Auth::user()->customer_id !== $profile->customer_id) {
            abort(403);
        }

        $profile->update($request->validated());

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
