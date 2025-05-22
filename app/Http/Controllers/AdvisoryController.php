<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdvisoryRequest;
use App\Http\Requests\UpdateAdvisoryRequest;
use App\Models\Advisory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvisoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $latestAdvisory = Advisory::select('id', 'headline', 'description', 'content', 'attachment', 'created_at')
        ->orderBy('created_at', 'desc')
        ->first();
    
    $moreAdvisories = Advisory::select('id', 'headline', 'description', 'content', 'attachment', 'created_at')
        ->orderBy('created_at', 'desc')
        ->skip(1)
        ->take(3)
        ->get();

        return view('advisories', compact('latestAdvisory', 'moreAdvisories'));
    }
    public function loadMore(Request $request)
    {
        return Advisory::latest()
            ->where('id', '!=', Advisory::latest()->value('id'))
            ->skip($request->skip ?? 0)
            ->take(5)
            ->get();
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

        // Handle file upload if it exists
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('advisory_attachments', 'public');
            $validatedRequest['attachment'] = $filePath;
        }

        $validatedRequest['created_by'] = auth()->id();

        Advisory::create($validatedRequest);

        return redirect()->back()->with('success', 'Advisory created successfully.');
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
 public function update(UpdateAdvisoryRequest $request, Advisory $advisory)
    {
        $validated = $request->validated();

        $data = [
            'headline'    => $validated['edit_headline'],
            'description' => $validated['edit_description'],
            'content'     => $validated['edit_content'],
        ];

        if ($request->hasFile('edit_attachment')) {
            // Optionally delete the old file
            if ($advisory->attachment) {
                Storage::disk('public')->delete($advisory->attachment);
            }
            // Store the new file and set its path
            $data['attachment'] = $request
                ->file('edit_attachment')
                ->store('advisory_attachments', 'public');
        }

        // 4. Perform update
        $advisory->update($data);

        // 5. Redirect with success
        return redirect()
            ->route('advisories.list')
            ->with('success', 'Advisory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advisory $advisory)
    {
        //
    }
}
