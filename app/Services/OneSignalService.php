<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    private ?string $appId;
    private ?string $apiKey;
    private string $baseUrl = 'https://onesignal.com/api/v1';

    public function __construct()
    {
        $this->appId = config('services.onesignal.app_id');
        $this->apiKey = config('services.onesignal.rest_api_key');
    }

    /**
     * Send a notification to all users
     */
    public function sendNotificationToAll(string $message, array $data = []): array
    {
        // Check if configuration is valid
        if (empty($this->appId) || empty($this->apiKey)) {
            return [
                'success' => false,
                'error' => 'OneSignal configuration is missing. Please check your environment variables.',
                'config_issue' => true
            ];
        }

        $payload = [
            'app_id' => $this->appId,
            'included_segments' => ['All'],
            'contents' => [
                'en' => $message
            ],
            'headings' => [
                'en' => 'New Lead Submission'
            ],
            'data' => array_merge($data, [
                'notification_type' => 'new_registration',
                'web_url' => config('app.url') . '/form',
                'timestamp' => now()->toISOString()
            ]),
            'url' => config('app.url') . '/form', // Redirect URL when notification is clicked
        ];

        return $this->makeApiCall('notifications', $payload);
    }

    /**
     * Send a notification to specific users
     */
    public function sendNotificationToUsers(array $userIds, string $message, array $data = []): array
    {
        // Check if configuration is valid
        if (empty($this->appId) || empty($this->apiKey)) {
            return [
                'success' => false,
                'error' => 'OneSignal configuration is missing. Please check your environment variables.',
                'config_issue' => true
            ];
        }

        $payload = [
            'app_id' => $this->appId,
            'include_external_user_ids' => $userIds,
            'contents' => [
                'en' => $message
            ],
            'headings' => [
                'en' => 'New Lead Submission'
            ],
            'data' => array_merge($data, [
                'notification_type' => 'new_registration',
                'web_url' => config('app.url') . '/form',
                'timestamp' => now()->toISOString()
            ]),
        ];

        return $this->makeApiCall('notifications', $payload);
    }

    /**
     * Make API call to OneSignal
     */
    private function makeApiCall(string $endpoint, array $payload): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/' . $endpoint, $payload);

            $responseData = $response->json();

            if ($response->successful()) {
                Log::info('OneSignal notification sent successfully', [
                    'notification_id' => $responseData['id'] ?? null,
                    'recipients' => $responseData['recipients'] ?? null
                ]);

                return [
                    'success' => true,
                    'data' => $responseData,
                    'message' => 'Notification sent successfully'
                ];
            } else {
                $errorMessage = 'OneSignal API error';
                if (isset($responseData['errors'])) {
                    $errorMessage .= ': ' . (is_array($responseData['errors']) ? implode(', ', $responseData['errors']) : $responseData['errors']);
                }

                Log::error('OneSignal API call failed', [
                    'status' => $response->status(),
                    'response' => $responseData,
                    'payload' => $payload
                ]);

                return [
                    'success' => false,
                    'error' => $errorMessage,
                    'status' => $response->status(),
                    'response' => $responseData
                ];
            }
        } catch (\Exception $e) {
            Log::error('OneSignal network error', [
                'error' => $e->getMessage(),
                'endpoint' => $endpoint,
                'payload' => $payload
            ]);

            return [
                'success' => false,
                'error' => 'Network error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get notification history
     */
    public function getNotificationHistory(string $notificationId): array
    {
        return $this->makeApiCall("notifications/{$notificationId}?app_id={$this->appId}", []);
    }
}
