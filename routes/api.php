<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Api\ClientApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Client API routes
Route::group(['prefix' => 'clients', 'middleware' => 'api.key'], function () {
    Route::get('/', [ClientApiController::class, 'index']);
    Route::post('/', [ClientApiController::class, 'store']);
    Route::get('/{client}', [ClientApiController::class, 'show']);
    Route::put('/{client}', [ClientApiController::class, 'update']);
    Route::delete('/{client}', [ClientApiController::class, 'destroy']);
    
    // Client Notes API routes
    Route::get('/{client}/notes', [ClientApiController::class, 'getClientNotes']);
    Route::post('/{client}/notes', [ClientApiController::class, 'storeClientNote']);
    Route::delete('/notes/{note}', [ClientApiController::class, 'deleteClientNote']);
    
    // Client Documents API routes
    Route::get('/{client}/documents', [ClientApiController::class, 'getClientDocuments']);
    Route::post('/{client}/documents/upload', [ClientApiController::class, 'uploadDocument']);
    Route::delete('/documents/{documentId}', [ClientApiController::class, 'deleteClientDocument']);
});

// Get client details by encrypted ID (no API key required for signature links)
Route::get('/clients/signature/{encryptedId}', [ClientApiController::class, 'getClientByEncryptedId']);

// Client search API for Select2
Route::get('/clients/search', function (Request $request) {
    $search = $request->get('search');
    $page = $request->get('page', 1);
    
    $query = \App\Models\Client::select('id', 'forename', 'surname', 'email_address', 'mobile_number');
    
    if ($search) {
        // Check if search term starts with 'G0' and extract the ID part
        $idSearch = null;
        if (preg_match('/^G0(\d+)$/i', $search, $matches)) {
            $idSearch = $matches[1]; // Extract the numeric part after G0
        }
        
        $query->where(function($q) use ($search, $idSearch) {
            $q->where('forename', 'like', "%{$search}%")
              ->orWhere('surname', 'like', "%{$search}%")
              ->orWhere('email_address', 'like', "%{$search}%")
              ->orWhere('mobile_number', 'like', "%{$search}%");
            
            // If we have an ID search, also search by ID
            if ($idSearch) {
                $q->orWhere('id', $idSearch);
            }
        });
    }
    
    // Limit results to 20 per page for better performance
    $clients = $query->orderBy('forename')
                     ->orderBy('surname')
                     ->paginate(20, ['*'], 'page', $page);
    
    return response()->json($clients);
})->name('api.clients.search');

// Creditor search API for Select2
Route::get('/creditors/search', function (Request $request) {
    $search = $request->get('search');
    $page = $request->get('page', 1);
    
    $query = \App\Models\Creditor::select('id', 'name', 'email', 'creditor_type')
                                 ->where('is_active', true);
    
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('creditor_type', 'like', "%{$search}%");
        });
    }
    
    // Limit results to 20 per page for better performance
    $creditors = $query->orderBy('name')
                       ->paginate(20, ['*'], 'page', $page);
    
    return response()->json($creditors);
})->name('api.creditors.search');
