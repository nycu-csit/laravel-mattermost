# nycu-csit/laravel-mattermost

This package provides the custom Mattermost Notification Channel for Laravel 10+ applications.

## Installation

Add following lines to your `composer.json`:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://gitlab.it.cs.nycu.edu.tw/nycu-csit/laravel-mattermost"
    }
],
"require": {
    "nycu-csit/laravel-mattermost": "^1.0.0"
}
```

## Usage

```php
<?php

namespace App\Notifications;

use NycuCsit\LaravelMattermost\MattermostChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifyMeetingStartEnd extends Notification
{
    use Queueable;

    public function __construct(private Meeting $meeting)
    {
    }
    /**
     * Get the notification channels.
     */
    public function via(object $notifiable): string
    {
        return [MattermostChannel::class];
    }

    /**
     * Get the payload of the mattermost webhook.
     * See on the [official website](https://developers.mattermost.com/integrate/webhooks/incoming/#parameters).
     * 
     * Notification can exit early without actually sending to Mattermost
     * channel by returning from falsy value.
     */
    public function toMattermost(object $notifiable)
    {
        if ($this->meeting->started) {
            return null;
        }
        // https://developers.mattermost.com/integrate/webhooks/incoming/#parameters
        return [
            'text' => '...',
            'channel' => 'www', // 'general'
            '...' => '...',
        ];
    }
}
```

## Testing

```sh
./vendor/bin/phpunit
```
