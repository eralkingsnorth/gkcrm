<?php

namespace App\Http\Controllers\Creditor;

use App\Http\Controllers\Controller;
use App\Models\Creditor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreditorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $creditors = Creditor::orderBy('name')->get();
        
        return view('creditor.index', compact('creditors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('creditor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'creditor_type' => 'nullable|in:bank,credit_card,utility,loan,mortgage,debt_collection,other',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'town_city' => 'nullable|string|max:255',
            'county' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Creditor::create([
                'name' => $request->name,
                'email' => $request->email,
                'creditor_type' => $request->creditor_type,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'town_city' => $request->town_city,
                'county' => $request->county,
                'country' => $request->country ?: 'United Kingdom',
                'postcode' => $request->postcode,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('creditors.index')
                ->with('success', 'Creditor created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating creditor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Creditor $creditor)
    {
        return view('creditor.show', compact('creditor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Creditor $creditor)
    {
        return view('creditor.edit', compact('creditor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Creditor $creditor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'creditor_type' => 'nullable|in:bank,credit_card,utility,loan,mortgage,debt_collection,other',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'town_city' => 'nullable|string|max:255',
            'county' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $creditor->update([
                'name' => $request->name,
                'email' => $request->email,
                'creditor_type' => $request->creditor_type,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'town_city' => $request->town_city,
                'county' => $request->county,
                'country' => $request->country ?: 'United Kingdom',
                'postcode' => $request->postcode,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('creditors.index')
                ->with('success', 'Creditor updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating creditor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Creditor $creditor)
    {
        try {
            // Check if creditor is associated with any cases
            $caseCount = $creditor->cases()->count();
            if ($caseCount > 0) {
                return redirect()->route('creditors.index')
                    ->with('error', "Cannot delete creditor '{$creditor->name}' because it is associated with {$caseCount} case(s).");
            }

            $creditor->delete();
            return redirect()->route('creditors.index')
                ->with('success', 'Creditor deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('creditors.index')
                ->with('error', 'Error deleting creditor: ' . $e->getMessage());
        }
    }
}
