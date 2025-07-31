<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientNote;
use App\Models\LeadSource;
use App\Services\ClientNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ClientApiController extends Controller
{
    /**
     * Constructor - Apply API key middleware
     */
    public function __construct()
    {
        $this->middleware('api.key');
    }

    /**
     * Display a listing of clients.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Client::with(['clientNotes.creator', 'documents']);

            // Apply filters
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('forename', 'like', "%{$search}%")
                      ->orWhere('surname', 'like', "%{$search}%")
                      ->orWhere('email_address', 'like', "%{$search}%")
                      ->orWhere('mobile_number', 'like', "%{$search}%");
                });
            }

            if ($request->has('lead_source')) {
                $query->where('lead_source', $request->get('lead_source'));
            }

            if ($request->has('status')) {
                $query->where('client_status', $request->get('status'));
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $clients = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $clients->items(),
                'pagination' => [
                    'current_page' => $clients->currentPage(),
                    'last_page' => $clients->lastPage(),
                    'per_page' => $clients->perPage(),
                    'total' => $clients->total(),
                    'from' => $clients->firstItem(),
                    'to' => $clients->lastItem(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching clients: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch clients',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request, ClientNotificationService $notificationService): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'lead_source' => 'nullable|string|max:255',
                'title' => 'nullable|in:Mr,Mrs,Miss,Ms,Dr,Prof,Sir,Lady',
                'forename' => 'required|string|max:100',
                'surname' => 'required|string|max:100',
                'date_of_birth' => 'nullable|date|before:today',
                'country_of_birth' => 'nullable|string|max:100',
                'marital_status' => 'nullable|in:single,married,divorced,widowed,civil_partnership,separated',
                'email_address' => 'required|email|max:191',
                'mobile_number' => 'required|string|max:20',
                'home_phone' => 'nullable|string|max:20',
                'postcode' => 'nullable|string|max:20',
                'house_number' => 'nullable|string|max:20',
                'address_line_1' => 'nullable|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'address_line_3' => 'nullable|string|max:255',
                'town_city' => 'nullable|string|max:100',
                'county' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'other' => 'nullable|string',
                'notes' => 'nullable|string',
                'client_status' => 'nullable|in:active,inactive,pending,suspended',
                'client_notes' => 'nullable|array',
                'client_notes.*.content' => 'required_with:client_notes|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Set default status if not provided
            if (empty($validated['client_status'])) {
                $validated['client_status'] = 'active';
            }

            $client = Client::create($validated);

            // Create client notes if provided
            if ($request->has('client_notes') && is_array($request->client_notes)) {
                foreach ($request->client_notes as $noteData) {
                    $client->clientNotes()->create([
                        'content' => $noteData['content'],
                        'created_by' => Auth::id() ?? 1, // Default to user ID 1 if no auth
                    ]);
                }
            }

            // Load relationships for response
            $client->load(['clientNotes.creator', 'documents']);

            // Send welcome notifications (email and SMS)
            $notificationResults = $notificationService->sendWelcomeNotifications($client);

            $response = [
                'success' => true,
                'message' => 'Client created successfully',
                'data' => $client,
                'notifications' => [
                    'email_sent' => $notificationResults['email_sent'],
                    'sms_sent' => $notificationResults['sms_sent']
                ]
            ];

            // Add notification errors if any
            if ($notificationResults['email_error']) {
                $response['notifications']['email_error'] = $notificationResults['email_error'];
            }
            if ($notificationResults['sms_error']) {
                $response['notifications']['sms_error'] = $notificationResults['sms_error'];
            }

            return response()->json($response, 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating client: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client): JsonResponse
    {
        try {
            $client->load(['clientNotes.creator', 'documents']);

            return response()->json([
                'success' => true,
                'data' => $client
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching client: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, Client $client): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'lead_source' => 'nullable|string|max:255',
                'title' => 'nullable|in:Mr,Mrs,Miss,Ms,Dr,Prof,Sir,Lady',
                'forename' => 'required|string|max:100',
                'surname' => 'required|string|max:100',
                'date_of_birth' => 'nullable|date|before:today',
                'country_of_birth' => 'nullable|string|max:100',
                'marital_status' => 'nullable|in:single,married,divorced,widowed,civil_partnership,separated',
                'email_address' => 'required|email|max:191',
                'mobile_number' => 'required|string|max:20',
                'home_phone' => 'nullable|string|max:20',
                'postcode' => 'nullable|string|max:20',
                'house_number' => 'nullable|string|max:20',
                'address_line_1' => 'nullable|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'address_line_3' => 'nullable|string|max:255',
                'town_city' => 'nullable|string|max:100',
                'county' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'other' => 'nullable|string',
                'notes' => 'nullable|string',
                'client_status' => 'nullable|in:active,inactive,pending,suspended',
                // File upload validation
                'files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:20480',
                'document_types.*' => 'nullable|in:id_document,contract_document,financial_document,other_documents',
                'file_descriptions.*' => 'nullable|string|max:500',
                // Note validation
                'new_note' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();
            
            // Remove file and note fields from client update data
            unset($validated['files'], $validated['document_types'], $validated['file_descriptions'], $validated['new_note']);
            
            $client->update($validated);

            // Handle file uploads
            $uploadedFiles = [];
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                $documentTypes = $request->input('document_types', []);
                $descriptions = $request->input('file_descriptions', []);
                
                foreach ($files as $index => $file) {
                    $documentType = $documentTypes[$index] ?? 'other_documents';
                    $description = $descriptions[$index] ?? null;
                    
                    $uploadedFile = $this->uploadClientDocument($client, $file, $documentType, $description);
                    if ($uploadedFile) {
                        $uploadedFiles[] = $uploadedFile;
                    }
                }
            }

            // Handle new note
            $newNote = null;
            if ($request->filled('new_note')) {
                $newNote = $client->clientNotes()->create([
                    'content' => $request->new_note,
                    'created_by' => Auth::id() ?? 1,
                ]);
                $newNote->load('creator');
            }

            // Load relationships for response
            $client->load(['clientNotes.creator', 'documents']);

            $response = [
                'success' => true,
                'message' => 'Client updated successfully',
                'data' => $client
            ];

            if (!empty($uploadedFiles)) {
                $response['uploaded_files'] = $uploadedFiles;
            }

            if ($newNote) {
                $response['new_note'] = $newNote;
            }

            return response()->json($response);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating client: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified client.
     */
    public function destroy(Client $client): JsonResponse
    {
        try {
            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Client deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting client: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete client',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get client statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total_clients' => Client::count(),
                'active_clients' => Client::where('client_status', 'active')->count(),
                'inactive_clients' => Client::where('client_status', 'inactive')->count(),
                'pending_clients' => Client::where('client_status', 'pending')->count(),
                'suspended_clients' => Client::where('client_status', 'suspended')->count(),
                'clients_this_month' => Client::whereMonth('created_at', now()->month)->count(),
                'clients_this_year' => Client::whereYear('created_at', now()->year)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching client statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch client statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get lead sources for dropdown.
     */
    public function leadSources(): JsonResponse
    {
        try {
            $leadSources = LeadSource::orderBy('name')->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'data' => $leadSources
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching lead sources: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch lead sources',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get client details by encrypted ID for signature page
     */
    public function getClientByEncryptedId($encryptedId): JsonResponse
    {
        try {
            $clientUrlService = app(\App\Services\ClientUrlService::class);
            $client = $clientUrlService->getClientByEncryptedId($encryptedId);
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found or invalid signature link'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $client->id,
                    'forename' => $client->forename,
                    'surname' => $client->surname,
                    'email_address' => $client->email_address,
                    'mobile_number' => $client->mobile_number,
                    'date_of_birth' => $client->date_of_birth,
                    'address' => $this->formatAddress($client),
                    'case_reference' => 'E0' . str_pad($client->id, 6, '0', STR_PAD_LEFT)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve client details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get client notes
     */
    public function getClientNotes(Client $client): JsonResponse
    {
        try {
            $notes = $client->clientNotes()->with('creator')->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $notes
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching client notes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch client notes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new client note
     */
    public function storeClientNote(Request $request, Client $client): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $note = $client->clientNotes()->create([
                'content' => $request->content,
                'created_by' => Auth::id() ?? 1, // Default to user ID 1 if no auth
            ]);

            $note->load('creator');

            return response()->json([
                'success' => true,
                'message' => 'Note created successfully',
                'data' => $note
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating client note: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create note',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a client note
     */
    public function deleteClientNote(ClientNote $note): JsonResponse
    {
        try {
            $note->delete();

            return response()->json([
                'success' => true,
                'message' => 'Note deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting client note: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete note',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload a document for a client via API
     */
    public function uploadDocument(Request $request, Client $client): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'document_type' => 'required|in:id_document,contract_document,financial_document,other_documents',
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:20480', // 20MB max
                'description' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            $documentType = $request->document_type;
            $description = $request->description;

            $uploadedFile = $this->uploadClientDocument($client, $file, $documentType, $description);

            if (!$uploadedFile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload document'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'data' => $uploadedFile
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error uploading document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get client documents
     */
    public function getClientDocuments(Client $client): JsonResponse
    {
        try {
            $documents = $client->documents()->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $documents
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching client documents: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch client documents',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a client document
     */
    public function deleteClientDocument($documentId): JsonResponse
    {
        try {
            $document = \App\Models\ClientDocument::findOrFail($documentId);
            
            // Delete file from storage
            if (\Illuminate\Support\Facades\Storage::disk('local')->exists($document->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('local')->delete($document->file_path);
            }
            
            // Delete record from database
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting client document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format client address
     */
    private function formatAddress($client)
    {
        $addressParts = [];
        
        if ($client->house_number) {
            $addressParts[] = $client->house_number;
        }
        if ($client->address_line_1) {
            $addressParts[] = $client->address_line_1;
        }
        if ($client->address_line_2) {
            $addressParts[] = $client->address_line_2;
        }
        if ($client->address_line_3) {
            $addressParts[] = $client->address_line_3;
        }
        if ($client->town_city) {
            $addressParts[] = $client->town_city;
        }
        if ($client->postcode) {
            $addressParts[] = $client->postcode;
        }
        
        return implode(', ', $addressParts);
    }

    /**
     * Helper method to upload client document
     */
    private function uploadClientDocument($client, $file, $documentType, $description = null)
    {
        try {
            // Generate unique filename
            $fileName = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Create storage path: client-documents/year-month/client-id/
            $yearMonth = now()->format('Y-m');
            $storagePath = "client-documents/{$yearMonth}/{$client->id}";
            
            // Store the file
            $filePath = $file->storeAs($storagePath, $fileName, 'local');
            
            // Create document record
            $document = \App\Models\ClientDocument::create([
                'client_id' => $client->id,
                'document_type' => $documentType,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_name' => $fileName,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $description,
            ]);

            return $document->load('client');
        } catch (\Exception $e) {
            Log::error('Error in uploadClientDocument: ' . $e->getMessage());
            return null;
        }
    }
} 