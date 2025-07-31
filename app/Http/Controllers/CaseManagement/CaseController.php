<?php

namespace App\Http\Controllers\CaseManagement;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\CaseStatus;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    /**
     * Display a listing of the cases.
     */
    public function index()
    {
        return view('case.index');
    }

    /**
     * Show the form for creating a new case.
     */
    public function create()
    {
        $caseStatuses = CaseStatus::active()->orderBy('name')->get();
        return view('case.create', compact('caseStatuses'));
    }

    /**
     * Store a newly created case in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement case creation logic
        return redirect()->route('cases.index')->with('success', 'Case created successfully');
    }

    /**
     * Display the specified case.
     */
    public function show($id)
    {
        return view('case.show');
    }

    /**
     * Show the form for editing the specified case.
     */
    public function edit($id)
    {
        return view('case.edit');
    }

    /**
     * Update the specified case in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Implement case update logic
        return redirect()->route('cases.index')->with('success', 'Case updated successfully');
    }

    /**
     * Remove the specified case from storage.
     */
    public function destroy($id)
    {
        // TODO: Implement case deletion logic
        return redirect()->route('cases.index')->with('success', 'Case deleted successfully');
    }
} 