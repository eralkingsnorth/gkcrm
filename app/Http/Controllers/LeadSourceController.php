<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadSource;

class LeadSourceController extends Controller
{
    /**
     * Display a listing of lead sources.
     */
    public function index()
    {
        $leadSources = LeadSource::orderBy('name')->get();
        return view('lead-source.index', compact('leadSources'));
    }

    /**
     * Show the form for creating a new lead source.
     */
    public function create()
    {
        return view('lead-source.create');
    }

    /**
     * Store a newly created lead source in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:lead_sources,name',
        ]);

        LeadSource::create($validated);

        return redirect()->route('lead-sources.index')->with('success', 'Lead source created successfully');
    }

    /**
     * Display the specified lead source.
     */
    public function show($id)
    {
        $leadSource = LeadSource::findOrFail($id);
        return view('lead-source.show', compact('leadSource'));
    }

    /**
     * Show the form for editing the specified lead source.
     */
    public function edit($id)
    {
        $leadSource = LeadSource::findOrFail($id);
        return view('lead-source.edit', compact('leadSource'));
    }

    /**
     * Update the specified lead source in storage.
     */
    public function update(Request $request, $id)
    {
        $leadSource = LeadSource::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:lead_sources,name,' . $id,
        ]);

        $leadSource->update($validated);

        return redirect()->route('lead-sources.index')->with('success', 'Lead source updated successfully');
    }

    /**
     * Remove the specified lead source from storage.
     */
    public function destroy($id)
    {
        $leadSource = LeadSource::findOrFail($id);
        
        // Check if lead source is being used by any clients
        if ($leadSource->clients()->count() > 0) {
            return redirect()->route('lead-sources.index')->with('error', 'Cannot delete lead source that is being used by clients');
        }

        $leadSource->delete();

        return redirect()->route('lead-sources.index')->with('success', 'Lead source deleted successfully');
    }
} 