<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        @if($staticLastmod['home'])<lastmod>{{ \Illuminate\Support\Carbon::parse($staticLastmod['home'])->toAtomString() }}</lastmod>@endif
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('voice-bot') }}</loc>
        <lastmod>{{ \Illuminate\Support\Carbon::parse($staticLastmod['voice-bot'])->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('blog.index') }}</loc>
        @if($staticLastmod['blog.index'])<lastmod>{{ \Illuminate\Support\Carbon::parse($staticLastmod['blog.index'])->toAtomString() }}</lastmod>@endif
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('legal.privacidad') }}</loc>
        <lastmod>{{ \Illuminate\Support\Carbon::parse($staticLastmod['legal'])->toAtomString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('legal.terminos') }}</loc>
        <lastmod>{{ \Illuminate\Support\Carbon::parse($staticLastmod['legal'])->toAtomString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('legal.cookies') }}</loc>
        <lastmod>{{ \Illuminate\Support\Carbon::parse($staticLastmod['legal'])->toAtomString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('legal.calidad') }}</loc>
        <lastmod>{{ \Illuminate\Support\Carbon::parse($staticLastmod['legal'])->toAtomString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    @foreach($posts as $post)
    <url>
        <loc>{{ route('blog.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @foreach($categories as $category)
    <url>
        <loc>{{ route('blog.category', $category->slug) }}</loc>
        @if($category->latest_post_at)<lastmod>{{ \Illuminate\Support\Carbon::parse($category->latest_post_at)->toAtomString() }}</lastmod>@endif
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @foreach($tags as $tag)
    <url>
        <loc>{{ route('blog.tag', $tag->slug) }}</loc>
        @if($tag->latest_post_at)<lastmod>{{ \Illuminate\Support\Carbon::parse($tag->latest_post_at)->toAtomString() }}</lastmod>@endif
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach
</urlset>
