<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get API key from header or query parameter
        $apiKey = $request->header('X-API-Key') ?? $request->query('api_key');
        
        // Valid API keys (in production, these should be stored in database)
        $validApiKeys = [
            'gkcrm_api_key_2024_secure_12345',
            'gkcrm_test_key_67890',
            'gkcrm_demo_key_abc123'
        ];
        
        // Check if API key is provided and valid
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key is required',
                'error' => 'Missing X-API-Key header or api_key parameter'
            ], 401);
        }
        
        if (!in_array($apiKey, $validApiKeys)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key',
                'error' => 'The provided API key is not valid'
            ], 401);
        }
        
        // Add API key to request for logging purposes
        $request->merge(['api_key_used' => $apiKey]);
        
        return $next($request);
    }
} 