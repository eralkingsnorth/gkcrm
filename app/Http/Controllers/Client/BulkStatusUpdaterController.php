<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BulkStatusUpdaterController extends Controller
{
    /**
     * Show the bulk status updater form.
     */
    public function index()
    {
        return view('client.bulk-status-updater');
    }

    /**
     * Process bulk status update request.
     */
    public function updateStatus(Request $request)
    {
        // Add validation
        $request->validate([
            'client_ids' => 'required|array',
            'status' => 'required|string'
        ]);

        // Add logic for updating status of multiple clients
        
        return redirect()->route('clients.index')->with('success', 'Client statuses updated successfully');
    }
} 