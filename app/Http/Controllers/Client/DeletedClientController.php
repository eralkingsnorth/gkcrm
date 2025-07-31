<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeletedClientController extends Controller
{
    /**
     * Display a listing of deleted clients.
     */
    public function index()
    {
        return view('client.deleted');
    }

    /**
     * Restore a deleted client.
     */
    public function restore($id)
    {
        // Add logic to restore a soft-deleted client
        
        return redirect()->route('clients.deleted')->with('success', 'Client restored successfully');
    }

    /**
     * Permanently delete a client.
     */
    public function forceDelete($id)
    {
        // Add logic to permanently delete a client
        
        return redirect()->route('clients.deleted')->with('success', 'Client permanently deleted');
    }
} 