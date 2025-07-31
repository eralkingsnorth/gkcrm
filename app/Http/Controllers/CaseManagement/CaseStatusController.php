<?php

namespace App\Http\Controllers\CaseManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CaseStatus;

class CaseStatusController extends Controller
{
    /**
     * Display a listing of case statuses.
     */
    public function index()
    {
        $caseStatuses = CaseStatus::orderBy('name')->get();
        return view('case.status.index', compact('caseStatuses'));
    }

    /**
     * Show the form for creating a new case status.
     */
    public function create()
    {
        return view('case.status.create');
    }

    /**
     * Store a newly created case status in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:case_statuses,name',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        CaseStatus::create($validated);

        return redirect()->route('case-statuses.index')->with('success', 'Case status created successfully');
    }

    /**
     * Display the specified case status.
     */
    public function show($id)
    {
        $caseStatus = CaseStatus::findOrFail($id);
        return view('case.status.show', compact('caseStatus'));
    }

    /**
     * Show the form for editing the specified case status.
     */
    public function edit($id)
    {
        $caseStatus = CaseStatus::findOrFail($id);
        return view('case.status.edit', compact('caseStatus'));
    }

    /**
     * Update the specified case status in storage.
     */
    public function update(Request $request, $id)
    {
        $caseStatus = CaseStatus::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:case_statuses,name,' . $id,
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $caseStatus->update($validated);

        return redirect()->route('case-statuses.index')->with('success', 'Case status updated successfully');
    }

    /**
     * Remove the specified case status from storage.
     */
    public function destroy($id)
    {
        $caseStatus = CaseStatus::findOrFail($id);
        
        // Check if status is being used by any cases
        if ($caseStatus->cases()->count() > 0) {
            return redirect()->route('case-statuses.index')->with('error', 'Cannot delete status that is being used by cases');
        }

        $caseStatus->delete();

        return redirect()->route('case-statuses.index')->with('success', 'Case status deleted successfully');
    }
} 