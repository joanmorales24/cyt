<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'wp_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'featured_image_alt',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
        'seo_focus_keyword',
        'seo_canonical_url',
        'og_image',
        'old_slug',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSeoTitleAttribute($value): string
    {
        return $value ?: $this->title . ' | CyT Comunicaciones';
    }

    public function getContentHtmlAttribute(): string
    {
        $raw    = $this->content ?? '';
        $blocks = json_decode($raw, true);
        if (! is_array($blocks)) return $raw; // HTML legacy → mostrar tal cual

        $html = '';
        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';
            $data = $block['data'] ?? [];
            $html .= match ($type) {
                'paragraph' => $this->renderParagraph($data),
                'heading'   => $this->renderHeading($data),
                'image'     => $this->renderImage($data),
                'video'     => $this->renderVideo($data),
                'quote'     => $this->renderQuote($data),
                'list'      => $this->renderList($data),
                'divider'   => '<hr>',
                'code'      => '<pre><code>' . e($data['code'] ?? '') . '</code></pre>',
                'html'      => $data['html'] ?? '',
                default     => '',
            };
        }
        return $html;
    }

    private function renderParagraph(array $data): string
    {
        $align = $data['align'] ?? 'left';
        $style = $align !== 'left' ? " style=\"text-align:{$align}\"" : '';
        $text = strip_tags($data['text'] ?? '', '<b><i><strong><em><a><br><code><mark>');
        return '<p' . $style . '>' . $text . '</p>';
    }

    private function renderHeading(array $data): string
    {
        $tag   = in_array($data['level'] ?? 'h2', ['h2','h3','h4']) ? $data['level'] : 'h2';
        $align = $data['align'] ?? 'left';
        $style = $align !== 'left' ? " style=\"text-align:{$align}\"" : '';
        $text = strip_tags($data['text'] ?? '', '<b><i><strong><em><a><br><code><mark>');
        return "<{$tag}{$style}>" . $text . "</{$tag}>";
    }

    private function renderImage(array $data): string
    {
        if (empty($data['src'])) return '';

        $raw = $data['src'];
        // Si es ruta relativa del disk public → convertir a URL pública
        $src = str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')
            ? $raw
            : \Illuminate\Support\Facades\Storage::disk('public')->url($raw);

        $alt     = e($data['alt'] ?? '');
        $caption = $data['caption'] ?? '';
        $fig     = '<figure><img src="' . e($src) . '" alt="' . $alt . '" style="max-width:100%">';
        if ($caption) $fig .= '<figcaption>' . e($caption) . '</figcaption>';
        return $fig . '</figure>';
    }

    private function renderVideo(array $data): string
    {
        if (empty($data['url'])) return '';
        $url = $this->toEmbedUrl($data['url']);
        return '<div class="video-wrap" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:1rem">'
            . '<iframe src="' . e($url) . '" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;border-radius:1rem"></iframe>'
            . '</div>';
    }

    private function renderQuote(array $data): string
    {
        $text = strip_tags($data['text'] ?? '', '<b><i><strong><em><a><br><code><mark>');
        $cite = strip_tags($data['cite'] ?? '', '<b><i><strong><em><a><br>');
        $out  = '<blockquote><p>' . $text . '</p>';
        if ($cite) $out .= '<cite>' . $cite . '</cite>';
        return $out . '</blockquote>';
    }

    private function renderList(array $data): string
    {
        $tag   = ($data['style'] ?? 'unordered') === 'ordered' ? 'ol' : 'ul';
        $items = $data['items'] ?? [];
        $html  = "<{$tag}>";
        foreach ($items as $item) {
            $itemText = is_array($item) ? ($item['text'] ?? '') : $item;
            $itemText = strip_tags($itemText, '<b><i><strong><em><a><br><code><mark>');
            $html .= '<li>' . $itemText . '</li>';
        }
        return $html . "</{$tag}>";
    }

    private function toEmbedUrl(string $url): string
    {
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }
        return $url;
    }
}
