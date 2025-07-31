<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientDocumentController extends Controller
{
    /**
     * Upload a document for a client.
     */
    public function upload(Request $request, $clientId)
    {
        $request->validate([
            'document_type' => 'required|in:id_document,contract_document,financial_document,other_documents',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:20480', // 20MB max
            'description' => 'nullable|string|max:500',
        ]);

        $client = Client::findOrFail($clientId);
        $file = $request->file('file');
        
        // Generate unique filename
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Create storage path: client-documents/year-month/client-id/
        $yearMonth = now()->format('Y-m');
        $storagePath = "client-documents/{$yearMonth}/{$clientId}";
        
        // Store the file
        $filePath = $file->storeAs($storagePath, $fileName, 'local');
        
        // Create document record
        $document = ClientDocument::create([
            'client_id' => $clientId,
            'document_type' => $request->document_type,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_name' => $fileName,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully',
            'document' => $document->load('client'),
        ]);
    }

    /**
     * Download a document.
     */
    public function download($documentId)
    {
        $document = ClientDocument::findOrFail($documentId);
        
        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('local')->download($document->file_path, $document->original_name);
    }

    /**
     * View a document (for images and PDFs).
     */
    public function view($documentId)
    {
        $document = ClientDocument::findOrFail($documentId);
        
        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        $file = Storage::disk('local')->get($document->file_path);
        
        return response($file, 200, [
            'Content-Type' => $document->mime_type,
            'Content-Disposition' => 'inline; filename="' . $document->original_name . '"',
        ]);
    }

    /**
     * Delete a document.
     */
    public function delete($documentId)
    {
        $document = ClientDocument::findOrFail($documentId);
        
        // Delete file from storage
        if (Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }
        
        // Delete record from database
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully',
        ]);
    }

    /**
     * Get documents for a client.
     */
    public function getClientDocuments($clientId)
    {
        $client = Client::findOrFail($clientId);
        $documents = $client->documents()->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'documents' => $documents,
        ]);
    }
} 