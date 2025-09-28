<?php

namespace NycuCsit\LaravelMattermost;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Conditionable;
use LogicException;

/**
 * https://developers.mattermost.com/integrate/webhooks/incoming/#parameters
 */
class MattermostMessage implements Arrayable
{
    use Conditionable;

    protected ?string $text = null;

    protected ?string $channel = null;

    protected ?string $username = null;

    protected ?string $icon_url = null;

    protected ?string $icon_emoji = null;

    /** @var array<MattermostAttachment|array<mixed>> */
    protected array $attachments = [];

    protected ?string $type = null;

    protected ?array $priority = null;

    public function text(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function channel(string $channel): self
    {
        $this->channel = $channel;
        return $this;
    }

    public function username(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function iconUrl(string $icon_url): self
    {
        $this->icon_url = $icon_url;
        return $this;
    }

    public function iconEmoji(string $icon_emoji): self
    {
        $this->icon_emoji = $icon_emoji;
        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function attachment(Closure $callback): self
    {
        $this->attachments[] = $attachment = new MattermostAttachment();
        $callback($attachment);
        return $this;
    }

    public function priority(string $priority, ?bool $requested_ack, ?bool $persistent_notifications): self
    {
        $this->priority = [
            'priority' => $priority,
            'requested_ack' => $requested_ack,
            'persistent_notifications' => $persistent_notifications,
        ];
        return $this;
    }

    public function toArray(): array
    {
        if ($this->text === null && empty($this->attachments)) {
            throw new LogicException('Mattermost message must contain at least a text or an attachment.');
        }

        return array_filter([
            'text'        => $this->text,
            'channel'     => $this->channel,
            'username'    => $this->username,
            'icon_url'    => $this->icon_url,
            'icon_emoji'  => $this->icon_emoji,
            'attachments' => !empty($this->attachments)
                ? array_map(fn ($a) => $a instanceof MattermostAttachment ? $a->toArray() : $a, $this->attachments)
                : null,
            'type'        => $this->type,
            'priority'    => $this->priority,
        ], fn ($value) => $value !== null);
    }
}
