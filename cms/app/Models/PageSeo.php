<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageSeo extends Model
{
    protected $table = 'page_seo';

    protected $fillable = [
        'page', 'title', 'description', 'canonical_url',
        'og_image', 'robots', 'focus_keyword',
    ];

    public static function forPage(string $page): ?self
    {
        $data = Cache::remember("page_seo_{$page}", 3600, fn () =>
            static::where('page', $page)->first()?->toArray()
        );

        return $data ? (new static)->forceFill($data) : null;
    }

    protected static function booted(): void
    {
        static::saved(fn ($m)   => Cache::forget("page_seo_{$m->page}"));
        static::deleted(fn ($m) => Cache::forget("page_seo_{$m->page}"));
    }
}
