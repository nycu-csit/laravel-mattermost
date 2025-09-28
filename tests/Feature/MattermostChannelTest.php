<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use NycuCsit\LaravelMattermost\MattermostChannel;
use Orchestra\Testbench\TestCase;

class MattermostChannelTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('services.mattermost.webhook_url', 'http://dummy.host');
    }

    public function testNormal()
    {
        Http::fake([
            config('services.mattermost.webhook_url') => Http::response(),
        ]);

        $notification = new MattermostNotification(false);
        $channel = new MattermostChannel();
        $res = $channel->send((object) [], $notification);
        $this->assertEquals($res, true);
    }

    public function testEarlyExit()
    {
        Http::fake([
            config('services.mattermost.webhook_url') => Http::response(),
        ]);

        $notification = new MattermostNotification(true);
        $channel = new MattermostChannel();
        $res = $channel->send((object) [], $notification);
        $this->assertEquals($res, false);
    }

    public function testNotOkResp()
    {
        Http::fake([
            config('services.mattermost.webhook_url') => Http::response(null, 400),
        ]);

        $notification = new MattermostNotification(false);
        $channel = new MattermostChannel();
        $res = $channel->send((object) [], $notification);
        $this->assertEquals($res, false);
    }

    public function testException()
    {
        $notification = new MattermostNotification(false);
        $channel = new MattermostChannel();
        $res = $channel->send((object) [], $notification);
        $this->assertEquals($res, false);
    }
}

// phpcs:ignore PSR1.Classes.ClassDeclaration.MultipleClasses
class MattermostNotification extends \Illuminate\Notifications\Notification
{
    public function __construct(private $earlyExit = false)
    {
    }

    public function toMattermost(object $notifiable)
    {
        if ($this->earlyExit) {
            return null;
        }

        return [
            'text' => 'this is test msg',
            'channel' => 'general',
        ];
    }
}
