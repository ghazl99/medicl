<?php

namespace Modules\Core\Traits;

use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Models\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

trait FirebaseNotificationTrait
{
    protected $messaging;

    /**
     * Initialize Firebase only once when the trait is loaded.
     * This ensures the Firebase connection is created a single time
     * and reused for all subsequent notifications in the same request.
     */
    public function bootFirebaseNotificationTrait()
    {
        if (!$this->messaging) {
            $factory = (new Factory)
                ->withServiceAccount(base_path('firebase_credentials.json'));
            $this->messaging = $factory->createMessaging();
        }
    }

    /**
     * Send an FCM notification and save it in the database.
     *
     * @param string $title    Notification title
     * @param string $body     Notification body
     * @param string|null $fcmToken Target device's FCM token
     * @param array|null $data  Additional custom payload data (optional)
     * @param int|null $userId  Related user ID for storing in DB (optional)
     * @param string|null $url  Optional URL related to the notification
     * @return bool             True on success, false on failure
     */
    public function sendFirebaseNotification(
        string $title,
        string $body,
        ?string $fcmToken,
        ?array $data = null,
        ?int $userId = null,
        ?string $url = null
    ): bool {
        if (!$fcmToken) {
            Log::warning("Firebase Token not provided.");
            return false;
        }

        $this->bootFirebaseNotificationTrait();

        try {

            $notification = FirebaseNotification::create($title, $body,asset('assets/img/capsule.png'));

            $message = CloudMessage::new()
                ->toToken($fcmToken)
                ->withNotification($notification);

            if ($data) {
                $message = $message->withData($data);
            }

            try {
                $this->messaging->send($message);
            } catch (\Throwable $e) {
                Log::error("Firebase Send Error: " . $e->getMessage());
                return false;
            }

            Notification::create([
                'user_id'    => $userId,
                'title'      => $title,
                'body'       => $body,
                'data'       => $data,
                'url'        => $url,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error("Firebase Notification Error: " . $e->getMessage());
            return false;
        }
    }
}
