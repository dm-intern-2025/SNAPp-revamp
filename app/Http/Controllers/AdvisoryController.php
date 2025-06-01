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
        $latestAdvisory = Advisory::where('is_archive', false)
            ->orderBy('created_at', 'desc')
            ->first();

        $moreAdvisories = Advisory::where('is_archive', false)
            ->where('id', '!=', optional($latestAdvisory)->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('advisories', compact('latestAdvisory', 'moreAdvisories'));
    }

    public function loadMore(Request $request)
    {
        return Advisory::where('is_archive', false)
            ->where('id', '!=', Advisory::where('is_archive', false)->latest()->value('id'))
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
     * Update the specified resource in storage.
     */
    public function update(UpdateAdvisoryRequest $request, Advisory $advisory)
    {
        $validated = $request->validated();

        $data = [
            'headline'    => $validated['edit_headline'],
            'description' => $validated['edit_description'],
            'content'     => $validated['edit_content'],
            'is_archive'  => $validated['is_archive'] ?? 0,
        ];

        if ($request->hasFile('edit_attachment')) {
            if ($advisory->attachment) {
                Storage::disk('public')->delete($advisory->attachment);
            }

            $data['attachment'] = $request
                ->file('edit_attachment')
                ->store('advisory_attachments', 'public');
        }

        $advisory->update($data);

        return redirect()
            ->route('advisories.list')
            ->with('success', 'Advisory updated successfully.');
    }
}
