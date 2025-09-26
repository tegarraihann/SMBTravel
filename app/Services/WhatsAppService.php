<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $provider;
    protected string $apiUrl;
    protected string $token;

    public function __construct()
    {
        $this->provider = config('services.whatsapp.provider', 'fonnte');
        $this->apiUrl = config('services.whatsapp.api_url', 'https://api.fonnte.com/send');
        $this->token = config('services.whatsapp.token', '');
    }

    /**
     * Send WhatsApp message via Fonnte
     */
    public function sendMessage(string $phoneNumber, string $message): array
    {
        if (empty($this->token)) {
            return [
                'success' => false,
                'message' => 'WhatsApp token not configured',
                'error' => 'WHATSAPP_TOKEN not set in environment'
            ];
        }

        try {
            // Clean and format phone number
            $cleanPhone = $this->formatPhoneNumber($phoneNumber);

            if ($this->provider === 'fonnte') {
                return $this->sendViaFonnte($cleanPhone, $message);
            } else {
                return $this->sendViaBusinessAPI($cleanPhone, $message);
            }

        } catch (\Exception $e) {
            Log::error('WhatsApp send failed', [
                'phone' => $phoneNumber,
                'provider' => $this->provider,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send WhatsApp message',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send message via Fonnte API
     */
    protected function sendViaFonnte(string $phoneNumber, string $message): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->asForm()->post($this->apiUrl, [
                'target' => $phoneNumber,
                'message' => $message,
                'countryCode' => '62', // Indonesia country code
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['status']) && $responseData['status']) {
                Log::info('WhatsApp message sent via Fonnte successfully', [
                    'phone' => $phoneNumber,
                    'response' => $responseData
                ]);

                return [
                    'success' => true,
                    'message' => 'WhatsApp message sent successfully via Fonnte',
                    'response' => $responseData,
                    'provider' => 'fonnte'
                ];
            }

            // Handle Fonnte error response
            $errorMessage = $responseData['reason'] ?? $responseData['message'] ?? 'Unknown error from Fonnte';

            Log::error('Fonnte API error', [
                'phone' => $phoneNumber,
                'response' => $responseData,
                'http_status' => $response->status()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send via Fonnte: ' . $errorMessage,
                'response' => $responseData,
                'provider' => 'fonnte'
            ];

        } catch (\Exception $e) {
            Log::error('Fonnte API exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Fonnte API exception: ' . $e->getMessage(),
                'provider' => 'fonnte'
            ];
        }
    }

    /**
     * Send message via WhatsApp Business API (fallback)
     */
    protected function sendViaBusinessAPI(string $phoneNumber, string $message): array
    {
        try {
            $phoneNumberId = config('services.whatsapp.phone_number_id');
            $businessApiUrl = "https://graph.facebook.com/v18.0/{$phoneNumberId}/messages";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json',
            ])->post($businessApiUrl, [
                'messaging_product' => 'whatsapp',
                'to' => $phoneNumber,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent via Business API successfully', [
                    'phone' => $phoneNumber,
                    'response' => $response->json()
                ]);

                return [
                    'success' => true,
                    'message' => 'WhatsApp message sent successfully via Business API',
                    'response' => $response->json(),
                    'provider' => 'business_api'
                ];
            }

            throw new \Exception('Business API request failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('WhatsApp Business API failed', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Business API failed: ' . $e->getMessage(),
                'provider' => 'business_api'
            ];
        }
    }

    /**
     * Format phone number for WhatsApp (Indonesian format)
     */
    public function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-numeric characters
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Handle Indonesian phone number formatting
        if (substr($cleanPhone, 0, 1) === '0') {
            // Remove leading 0 and add 62
            $cleanPhone = '62' . substr($cleanPhone, 1);
        } elseif (substr($cleanPhone, 0, 2) !== '62') {
            // Add 62 if not present
            $cleanPhone = '62' . $cleanPhone;
        }

        return $cleanPhone;
    }

    /**
     * Validate Indonesian phone number
     */
    public function isValidPhoneNumber(string $phoneNumber): bool
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Indonesian phone numbers:
        // - Mobile: 62 + 8xx (10-13 digits total)
        // - Should be between 10-15 digits
        if (strlen($cleanPhone) < 10 || strlen($cleanPhone) > 15) {
            return false;
        }

        // Check if starts with 62 (Indonesia) and mobile prefix 8
        $formatted = $this->formatPhoneNumber($phoneNumber);
        return substr($formatted, 0, 3) === '628';
    }

    /**
     * Get current provider being used
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * Test WhatsApp connection
     */
    public function testConnection(): array
    {
        if (empty($this->token)) {
            return [
                'success' => false,
                'message' => 'WhatsApp token not configured'
            ];
        }

        try {
            if ($this->provider === 'fonnte') {
                // Test Fonnte connection with validate endpoint
                $response = Http::withHeaders([
                    'Authorization' => $this->token,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])->asForm()->post('https://api.fonnte.com/validate');

                $data = $response->json();

                if ($response->successful()) {
                    // For Fonnte, if we get response with reason "target invalid",
                    // it means API is working but no target provided (which is expected for validate)
                    $isApiWorking = isset($data['reason']) && $data['reason'] === 'target invalid';

                    return [
                        'success' => $isApiWorking,
                        'message' => $isApiWorking ? 'Fonnte API connection successful' : ($data['reason'] ?? 'Unknown status'),
                        'data' => $data,
                        'provider' => 'fonnte'
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Fonnte API error: ' . ($data['reason'] ?? 'Unknown error'),
                    'data' => $data,
                    'provider' => 'fonnte'
                ];
            }

            return [
                'success' => true,
                'message' => 'Business API connection cannot be tested without sending a message',
                'provider' => 'business_api'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage(),
                'provider' => $this->provider
            ];
        }
    }
}