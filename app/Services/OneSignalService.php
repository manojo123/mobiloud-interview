<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    private string $appId;
    private string $apiKey;
    private string $baseUrl = 'https://onesignal.com/api/v1';

    public function __construct()
    {
        $this->appId = config('services.onesignal.app_id');
        $this->apiKey = config('services.onesignal.api_key');
    }

    /**
     * Send a notification to all users
     */
    public function sendNotificationToAll(string $message, array $data = []): array
    {
        $payload = [
            'app_id' => $this->appId,
            'included_segments' => ['All'],
            'contents' => [
                'en' => $message
            ],
            'headings' => [
                'en' => 'New Lead Submission'
            ],
            'data' => $data,
            'url' => config('app.url') . '/admin/leads', // Optional: redirect URL
        ];

        return $this->makeApiCall('notifications', $payload);
    }

    /**
     * Send a notification to specific users
     */
    public function sendNotificationToUsers(array $userIds, string $message, array $data = []): array
    {
        $payload = [
            'app_id' => $this->appId,
            'include_external_user_ids' => $userIds,
            'contents' => [
                'en' => $message
            ],
            'headings' => [
                'en' => 'New Lead Submission'
            ],
            'data' => $data,
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
                    'endpoint' => $endpoint,
                    'payload' => $payload,
                    'response' => $responseData
                ]);

                return [
                    'success' => true,
                    'data' => $responseData,
                    'message' => 'Notification sent successfully'
                ];
            } else {
                Log::error('OneSignal API error', [
                    'endpoint' => $endpoint,
                    'payload' => $payload,
                    'status' => $response->status(),
                    'response' => $responseData
                ]);

                return [
                    'success' => false,
                    'error' => $responseData['errors'] ?? 'Unknown error',
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('OneSignal API exception', [
                'endpoint' => $endpoint,
                'payload' => $payload,
                'exception' => $e->getMessage()
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
