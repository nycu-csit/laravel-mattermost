<?php

namespace NycuCsit\LaravelMattermost;

use Exception;
use LogicException;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MattermostChannel
{
    protected $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = config('services.mattermost.webhook_url');
        if (!$this->webhookUrl) {
            Log::error('Mattermost webhook URL is not defined in the config.');
        }
    }

    public function send(object $notifiable, Notification $notification)
    {
        // ignore sending Mattermost notification in local dev unless required
        if (!$this->webhookUrl) {
            return false;
        }

        if (!method_exists($notification, 'toMattermost')) {
            throw new Exception('Notifications sent via laravel-mattermost should implement `toMattermost` method.');
        }
        $data = $notification->toMattermost($notifiable);
        // to enable early exit for projects like Meeting
        // Meeting notification has been dispatched, queued, and meeting has
        // been started/ended, then no need to actually send Mattermost message
        if (!$data) {
            return false;
        }
        if (!($data instanceof MattermostMessage)) {
            throw new LogicException('toMattermost should return an instance of MattermostMessage');
        }

        try {
            $response = Http::post($this->webhookUrl, $data);
            $respOk = $response->successful();
            if (!$respOk) {
                Log::error('Failed to send Mattermost notification', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'data' => $data->toArray(),
                ]);
            }
            return $respOk;
        } catch (Exception $e) {
            Log::error('Exception when sending Mattermost notification', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
