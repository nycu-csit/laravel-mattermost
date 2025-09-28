<?php

namespace NycuCsit\LaravelMattermost;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Conditionable;

/**
 * https://developers.mattermost.com/integrate/reference/message-attachments/
 */
class MattermostAttachment implements Arrayable
{
    use Conditionable;

    private ?string $fallback   = null;
    private ?string $color      = null;
    private ?string $pretext    = null;
    private ?string $text       = null;

    private ?string $author_name = null;
    private ?string $author_icon = null;
    private ?string $author_link = null;

    private ?string $title      = null;
    private ?string $title_link = null;

    private array $fields = [];
    private ?string $image_url  = null;

    public function fallback(?string $fallback): self
    {
        $this->fallback = $fallback;
        return $this;
    }

    public function color(?string $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function pretext(?string $pretext): self
    {
        $this->pretext = $pretext;
        return $this;
    }

    public function text(?string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function authorName(?string $name): self
    {
        $this->author_name = $name;
        return $this;
    }

    public function authorIcon(?string $icon): self
    {
        $this->author_icon = $icon;
        return $this;
    }

    public function authorLink(?string $link): self
    {
        $this->author_link = $link;
        return $this;
    }

    public function title(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function titleLink(?string $link): self
    {
        $this->title_link = $link;
        return $this;
    }

    public function field(string $title, string $value, ?bool $short = true): self
    {
        $this->fields[] = [
            'title' => $title,
            'value' => $value,
            'short' => $short,
        ];
        return $this;
    }

    public function imageUrl(?string $url): self
    {
        $this->image_url = $url;
        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'fallback'    => $this->fallback,
            'color'       => $this->color,
            'pretext'     => $this->pretext,
            'text'        => $this->text,

            'author_name' => $this->author_name,
            'author_icon' => $this->author_icon,
            'author_link' => $this->author_link,

            'title'       => $this->title,
            'title_link'  => $this->title_link,

            'fields' => empty($this->fields) ? null : $this->fields,
            'image_url'   => $this->image_url,
        ], fn ($v) => $v !== null);
    }
}
