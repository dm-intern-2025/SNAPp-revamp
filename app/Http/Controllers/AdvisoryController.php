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
        $advisories = Advisory::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.advisory-management.advisory-list', compact('advisories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdvisoryRequest $request)
    {
        $validatedRequest = $request->validated();

        if ($request->hasFile('attachment')) {
            // This will use the *default disk* (public locally)
            // and save to 'snapp-advisory-attachments' folder.
            $filePath = $request->file('attachment')->store('snapp-advisory-attachments');
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
            // As per your clarification, no file deletion from storage (archive only)
            // This will use the *default disk* (gcs in prod, public locally)
            // and save to 'snapp-advisory-attachments' folder.
            $data['attachment'] = $request
                ->file('edit_attachment')
                ->store('snapp-advisory-attachments');
        }

        $advisory->update($data);

        return redirect()
            ->route('advisories.list')
            ->with('success', 'Advisory updated successfully.');
    }
}