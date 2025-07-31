<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\LeadSource;
use App\Services\ClientNotificationService;
use App\Services\EmailService;
use App\Services\SmsService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index()
    {
        $clients = Client::active()->orderBy('created_at', 'desc')->paginate(10);
        return view('client.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        $client = null;
        $leadSources = LeadSource::orderBy('name')->get();
        return view('client.create', compact('client', 'leadSources'));
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request, ClientNotificationService $notificationService)
    {
        try {
            $validated = $request->validate([
                'lead_source' => 'nullable|string|max:255',
                'title' => 'nullable|in:Mr,Mrs,Miss,Ms,Dr,Prof,Sir,Lady',
                'forename' => 'required|string|max:100',
                'surname' => 'required|string|max:100',
                'date_of_birth' => 'nullable|date',
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
            ]);
            
            // Handle notes from form
            if ($request->has('notes') && is_array($request->notes)) {
                $validated['notes'] = $request->notes;
            }

            $client = Client::create($validated);
            
            // Send welcome notifications (email and SMS)
            $notificationResults = $notificationService->sendWelcomeNotifications($client);
            
            $successMessage = 'Client created successfully';
            if ($notificationResults['email_sent']) {
                $successMessage .= '. Welcome email sent.';
            }
            if ($notificationResults['sms_sent']) {
                $successMessage .= '. Welcome SMS sent.';
            }
            
            return redirect()->route('clients.edit', $client->id)->with('success', $successMessage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('Client creation failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to create client: ' . $e->getMessage()]);
        }
    }



    /**
     * Show the form for editing the specified client.
     */
    public function edit($id)
    {
        $client = Client::with('clientNotes.creator')->findOrFail($id);
        $leadSources = LeadSource::orderBy('name')->get();
        return view('client.create', compact('client', 'leadSources'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'lead_source' => 'nullable|string|max:255',
            'title' => 'nullable|in:Mr,Mrs,Miss,Ms,Dr,Prof,Sir,Lady',
            'forename' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
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
        ]);

        // Handle notes from form
        if ($request->has('notes') && is_array($request->notes)) {
            $validated['notes'] = $request->notes;
        }

        $client->update($validated);

        return redirect()->route('clients.edit', $client->id)->with('success', 'Client updated successfully');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully');
    }

    /**
     * Resend welcome email to client
     */
    public function resendEmail($clientId, EmailService $emailService)
    {
        try {
            $client = Client::findOrFail($clientId);
            
            \Log::info('=== RESEND EMAIL DEBUG START ===');
            \Log::info('Attempting to resend email for client: ' . $clientId . ' to: ' . $client->email_address);
            
            $emailSent = $emailService->sendWelcomeEmail($client);
            
            \Log::info('Email resend result for client ' . $clientId . ': ' . ($emailSent ? 'SUCCESS' : 'FAILED'));
            \Log::info('Email sent value type: ' . gettype($emailSent));
            \Log::info('Email sent value: ' . var_export($emailSent, true));
            
            if ($emailSent === true) {
                \Log::info('Returning success response');
                $message = 'Welcome email resent successfully to ' . $client->email_address;
                
                // Return JSON response for AJAX requests
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => $message
                    ]);
                }
                
                return back()->with('success', $message);
            } else {
                \Log::info('Returning error response - email sent was: ' . var_export($emailSent, true));
                $message = 'Failed to resend welcome email. Please check the logs for details.';
                
                // Return JSON response for AJAX requests
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }
                
                return back()->with('error', $message);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to resend email for client ' . $clientId . ': ' . $e->getMessage());
            \Log::error('Exception trace: ' . $e->getTraceAsString());
            $message = 'An error occurred while resending the email: ' . $e->getMessage();
            
            // Return JSON response for AJAX requests
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ]);
            }
            
            return back()->with('error', $message);
        } finally {
            \Log::info('=== RESEND EMAIL DEBUG END ===');
        }
    }

    /**
     * Resend welcome SMS to client
     */
    public function resendSms($clientId, SmsService $smsService)
    {
        try {
            $client = Client::findOrFail($clientId);
            
            $smsSent = $smsService->sendWelcomeSms($client);
            
            if ($smsSent) {
                $message = 'Welcome SMS resent successfully to ' . $client->mobile_number;
                
                // Return JSON response for AJAX requests
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => $message
                    ]);
                }
                
                return back()->with('success', $message);
            } else {
                $message = 'Failed to resend welcome SMS. Please check the logs for details.';
                
                // Return JSON response for AJAX requests
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }
                
                return back()->with('error', $message);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to resend SMS for client ' . $clientId . ': ' . $e->getMessage());
            $message = 'An error occurred while resending the SMS.';
            
            // Return JSON response for AJAX requests
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ]);
            }
            
            return back()->with('error', $message);
        }
    }
} 