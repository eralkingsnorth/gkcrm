<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\BulkDownloadController;
use App\Http\Controllers\Client\BulkImportController;
use App\Http\Controllers\Client\BulkStatusUpdaterController;
use App\Http\Controllers\Client\DeletedClientController;
use App\Http\Controllers\CaseManagement\CaseController;
use App\Http\Controllers\CaseManagement\CaseStatusController;

use App\Http\Controllers\Creditor\CreditorController;

use App\Http\Controllers\Client\ClientDocumentController;
use App\Http\Controllers\Client\ClientNoteController;
use App\Http\Controllers\LeadSourceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/clear', function () {
    try {
        // Clearing caches
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        // Regenerating caches
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        // Optimizing the application
        Artisan::call('optimize');

        // Collecting feedback
        $output = [
            'cache_clear' => Artisan::output(),
            'route_clear' => Artisan::output(),
            'config_clear' => Artisan::output(),
            'view_clear' => Artisan::output(),
            // 'config_cache' => Artisan::output(),
            // 'route_cache' => Artisan::output(),
            // 'view_cache' => Artisan::output(),
            // 'optimize' => Artisan::output(),
        ];

        return response()->json([
            'message' => 'Application caches cleared and regenerated successfully!',
            'details' => $output,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'An error occurred while clearing caches: ' . $e->getMessage(),
        ], 500);
    }
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Client Routes
    // Main Client CRUD Routes (excluding show)
    Route::resource('clients', ClientController::class)->except(['show']);
    
    // Bulk Download Routes
    Route::get('/clients/bulk-download', [BulkDownloadController::class, 'index'])->name('clients.bulk-download');
    Route::post('/clients/bulk-download', [BulkDownloadController::class, 'download'])->name('clients.bulk-download.process');
    
    // Bulk Import Routes
    Route::get('/clients/bulk-import', [BulkImportController::class, 'index'])->name('clients.bulk-import');
    Route::post('/clients/bulk-import', [BulkImportController::class, 'import'])->name('clients.bulk-import.process');
    
    // Bulk Status Updater Routes
    Route::get('/clients/bulk-status-updater', [BulkStatusUpdaterController::class, 'index'])->name('clients.bulk-status-updater');
    Route::post('/clients/bulk-status-updater', [BulkStatusUpdaterController::class, 'updateStatus'])->name('clients.bulk-status-updater.process');
    
    // Deleted Clients Routes
    Route::get('/clients/deleted', [DeletedClientController::class, 'index'])->name('clients.deleted');
    Route::patch('/clients/{id}/restore', [DeletedClientController::class, 'restore'])->name('clients.restore');
    Route::delete('/clients/{id}/force-delete', [DeletedClientController::class, 'forceDelete'])->name('clients.force-delete');
    
    // Case Routes
    Route::resource('cases', CaseController::class);
    Route::resource('case-statuses', CaseStatusController::class);
    
    // Creditor Routes
    Route::resource('creditors', CreditorController::class);
    
    // Lead Source Routes
    Route::resource('lead-sources', LeadSourceController::class);
    
    // Client Document Routes
    Route::post('/clients/{clientId}/documents/upload', [ClientDocumentController::class, 'upload'])->name('client.documents.upload');
    Route::get('/documents/{documentId}/download', [ClientDocumentController::class, 'download'])->name('client.documents.download');
    Route::get('/documents/{documentId}/view', [ClientDocumentController::class, 'view'])->name('client.documents.view');
    Route::delete('/documents/{documentId}', [ClientDocumentController::class, 'delete'])->name('client.documents.delete');
    Route::get('/clients/{clientId}/documents', [ClientDocumentController::class, 'getClientDocuments'])->name('client.documents.list');
    
    // Client Notes Routes
    Route::post('/clients/{clientId}/notes', [ClientNoteController::class, 'store'])->name('client.notes.store');
    Route::get('/clients/{clientId}/notes', [ClientNoteController::class, 'getClientNotes'])->name('client.notes.list');
    Route::delete('/notes/{noteId}', [ClientNoteController::class, 'delete'])->name('client.notes.delete');
    
    // Client Notification Routes
    Route::post('/clients/{clientId}/resend-email', [ClientController::class, 'resendEmail'])->name('client.resend.email');
    Route::post('/clients/{clientId}/resend-sms', [ClientController::class, 'resendSms'])->name('client.resend.sms');
});
