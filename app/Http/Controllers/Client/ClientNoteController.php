<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientNote;
use Illuminate\Http\Request;

class ClientNoteController extends Controller
{
    /**
     * Store a newly created note.
     */
    public function store(Request $request, $clientId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $client = Client::findOrFail($clientId);

        $note = ClientNote::create([
            'client_id' => $clientId,
            'content' => $request->content,
            'created_by' => auth()->user()->id,
        ]);

        return redirect()->back()->with('success', 'Note added successfully!');
    }

    /**
     * Get notes for a client.
     */
    public function getClientNotes($clientId)
    {
        $client = Client::findOrFail($clientId);
        $notes = $client->notes()->with('creator')->get();
        
        return response()->json([
            'success' => true,
            'notes' => $notes,
            'count' => $notes->count(),
        ]);
    }

    /**
     * Delete a note.
     */
    public function delete($noteId)
    {
        $note = ClientNote::findOrFail($noteId);
        
        // Check if user can delete the note (only creator)
        if ($note->created_by !== auth()->user()->id) {
            return redirect()->back()->with('error', 'You are not authorized to delete this note');
        }
        
        $note->delete();

        return redirect()->back()->with('success', 'Note deleted successfully!');
    }
} 