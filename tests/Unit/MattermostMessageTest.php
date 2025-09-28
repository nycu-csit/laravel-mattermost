<?php

namespace Tests\Unit;

use NycuCsit\LaravelMattermost\MattermostAttachment;
use NycuCsit\LaravelMattermost\MattermostMessage;
use Orchestra\Testbench\TestCase;

class MattermostMessageTest extends TestCase
{
    public function testPayload()
    {
        $msg = (new MattermostMessage())
            ->text('### testing')
            ->channel('town-square')
            ->username('mattermost')
            ->iconUrl('icon.url')
            ->iconEmoji('ok')
            ->attachment(function (MattermostAttachment $a) {
                $a->fallback('test')
                ->color('#fff')
                ->pretext('pretext')
                ->text('attachment text')
                ->authorName('mattermost')
                ->authorIcon('icon')
                ->authorLink('mattermost.com')
                ->title('example attachment')
                ->titleLink('title.link')
                ->imageUrl('img.url')
                ->field('long field', 'testing a veryyyy long', false)
                ->field('col 1', 'testing');
            })
            ->attachment(function (MattermostAttachment $a) {
                $a->fallback('test 2')
                ->color('#fff')
                ->pretext('pretext')
                ->text('attachment text')
                ->authorName('mattermost')
                ->authorIcon('icon')
                ->authorLink('mattermost.com')
                ->title('example attachment')
                ->titleLink('title.link')
                ->imageUrl('img.url')
                ->field('long field', 'testing a veryyyy long', false)
                ->field('col 1', 'testing');
            })
            ->type('custom_mm')
            ->priority('urgent', false, true)
            ->toArray();

        $this->assertEquals($msg, [
            'text' => '### testing',
            'channel' => 'town-square',
            'username' => 'mattermost',
            'icon_url' => 'icon.url',
            'icon_emoji' => 'ok',
            'type' => 'custom_mm',
            'priority' => [
                'priority' => 'urgent',
                'requested_ack' => false,
                'persistent_notifications' => true,
            ],
            'attachments' => [
                [
                    'fallback' => 'test',
                    'color' => '#fff',
                    'pretext' => 'pretext',
                    'text' => 'attachment text',
                    'author_name' => 'mattermost',
                    'author_icon' => 'icon',
                    'author_link' => 'mattermost.com',
                    'title' => 'example attachment',
                    'title_link' => 'title.link',
                    'fields' => [
                        [
                            'title' => 'long field',
                            'value' => 'testing a veryyyy long',
                            'short' => false,
                        ],
                        [
                            'title' => 'col 1',
                            'value' => 'testing',
                            'short' => true,
                        ],
                    ],
                    'image_url' => 'img.url',
                ],
                [
                    'fallback' => 'test 2',
                    'color' => '#fff',
                    'pretext' => 'pretext',
                    'text' => 'attachment text',
                    'author_name' => 'mattermost',
                    'author_icon' => 'icon',
                    'author_link' => 'mattermost.com',
                    'title' => 'example attachment',
                    'title_link' => 'title.link',
                    'fields' => [
                        [
                            'title' => 'long field',
                            'value' => 'testing a veryyyy long',
                            'short' => false,
                        ],
                        [
                            'title' => 'col 1',
                            'value' => 'testing',
                            'short' => true,
                        ],
                    ],
                    'image_url' => 'img.url',
                ],
            ],
        ]);
    }
}
