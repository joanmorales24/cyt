@extends('layouts.blog')

@php
    $seoTitle    = $post->seo_title ?: ($post->title . ' | CYT Comunicaciones');
    $seoDesc     = $post->seo_description ?: Str::limit(strip_tags($post->excerpt ?? $post->content), 160);
    $canonicalUrl = $post->seo_canonical_url ?: route('blog.show', $post->slug);
    $ogImage     = $post->og_image ?? $post->featured_image;
    if ($ogImage && !Str::startsWith($ogImage, 'http')) {
        $ogImage = Storage::url($ogImage);
    }
@endphp

@section('seo_title', $seoTitle)
@section('seo_description', $seoDesc)
@section('seo_keywords', $post->seo_focus_keyword ?? '')
@section('og_type', 'article')
@section('og_image', $ogImage ?? '')
@section('seo_canonical_tag')
<link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@section('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "NewsArticle",
  "headline": "{{ addslashes($post->title) }}",
  "description": "{{ addslashes($seoDesc) }}",
  "datePublished": "{{ optional($post->published_at)->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "author": { "@@type": "Organization", "name": "CYT Comunicaciones" },
  "publisher": {
    "@@type": "Organization",
    "name": "CYT Comunicaciones",
    "url": "https://cytcomunicaciones.com"
  },
  "mainEntityOfPage": { "@@type": "WebPage", "@@id": "{{ $canonicalUrl }}" }
  @if($ogImage),"image": "{{ $ogImage }}"@endif
}
</script>
@endsection

@section('content')

{{-- Hero del post --}}
<section class="relative overflow-hidden px-6 py-16"
         style="background: radial-gradient(circle at top left, rgba(123,63,242,0.45), transparent 35%), radial-gradient(circle at 80% 20%, rgba(28,169,255,0.28), transparent 24%), linear-gradient(135deg, #1b0d44 0%, #0a0b25 48%, #080414 100%);">
    <div class="absolute inset-0 opacity-40"
         style="background-image: linear-gradient(rgba(255,255,255,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 44px 44px;"></div>
    <div class="relative mx-auto max-w-7xl">
        {{-- Breadcrumb --}}
        <nav class="mb-5 flex flex-wrap items-center gap-1.5 text-xs font-semibold text-muted/70">
            <a href="{{ route('blog.index') }}" class="hover:text-white transition">Blog</a>
            @foreach($post->categories as $cat)
                <span class="text-muted/40">/</span>
                <a href="{{ route('blog.category', $cat->slug) }}" class="hover:text-white transition">{{ $cat->name }}</a>
            @endforeach
            <span class="text-muted/40">/</span>
            <span class="text-muted/60 line-clamp-1 max-w-[200px]">{{ Str::limit($post->title, 36) }}</span>
        </nav>

        {{-- Categorías --}}
        @if($post->categories->isNotEmpty())
            <div class="mb-4 flex flex-wrap gap-2">
                @foreach($post->categories as $cat)
                    <a href="{{ route('blog.category', $cat->slug) }}"
                       class="rounded-full border border-brandSoft/30 bg-brandSoft/10 px-3 py-1 text-xs font-extrabold uppercase tracking-[0.2em] text-cyan transition hover:bg-brandSoft/20">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <h1 class="max-w-3xl text-3xl font-extrabold leading-tight tracking-[-0.03em] text-white md:text-4xl lg:text-5xl">
            {{ $post->title }}
        </h1>

        <div class="mt-5 flex flex-wrap items-center gap-4 text-sm text-muted/80">
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base text-brandSoft">calendar_today</span>
                <time datetime="{{ optional($post->published_at)->toIso8601String() }}">
                    {{ optional($post->published_at)->format('d \d\e F \d\e Y') }}
                </time>
            </div>
            @if($post->tags->isNotEmpty())
                <div class="flex flex-wrap gap-1.5">
                    @foreach($post->tags->take(4) as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}"
                           class="rounded-full bg-white/10 px-2.5 py-0.5 text-xs font-semibold text-muted transition hover:bg-white/20 hover:text-white">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

{{-- Contenido principal --}}
<div class="bg-[#fdf7ff] px-6 py-12">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col gap-12 lg:flex-row">

            {{-- Artículo --}}
            <article class="min-w-0 flex-1">

                {{-- Imagen destacada --}}
                @if($post->featured_image)
                    <figure class="mb-10 overflow-hidden rounded-[2rem] shadow-card">
                        <img src="{{ Str::startsWith($post->featured_image, 'http') ? $post->featured_image : Storage::url($post->featured_image) }}"
                             alt="{{ $post->featured_image_alt ?? $post->title }}"
                             class="w-full max-h-[480px] object-cover">
                    </figure>
                @endif

                {{-- Cuerpo --}}
                <div class="cyt-prose">
                    {!! $post->content_html !!}
                </div>

                {{-- Etiquetas del post --}}
                @if($post->tags->isNotEmpty())
                    <div class="mt-10 flex flex-wrap items-center gap-2 border-t border-brand/10 pt-8">
                        <span class="text-xs font-bold uppercase tracking-[0.22em] text-[#9e92b8]">Etiquetas:</span>
                        @foreach($post->tags as $tag)
                            <a href="{{ route('blog.tag', $tag->slug) }}"
                               class="rounded-full border border-brand/15 bg-brand/5 px-3 py-1 text-xs font-semibold text-brand transition hover:bg-brand/15">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Compartir --}}
                <div class="mt-8 rounded-[1.75rem] border border-brand/10 bg-white p-6 shadow-sm">
                    <p class="mb-3 text-xs font-extrabold uppercase tracking-[0.22em] text-[#9e92b8]">Compartir este artículo</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($canonicalUrl) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-full border border-brand/20 bg-brand/5 px-4 py-2 text-sm font-bold text-brand transition hover:bg-brand/15">
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.13 1.45-2.13 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.07 2.07 0 1 1 0-4.14 2.07 2.07 0 0 1 0 4.14zm1.78 13.02H3.56V9h3.56v11.45zM22.22 0H1.77C.8 0 0 .77 0 1.73v20.54C0 23.23.8 24 1.77 24h20.45C23.2 24 24 23.23 24 22.27V1.73C24 .77 23.2 0 22.22 0z"/></svg>
                            LinkedIn
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($canonicalUrl) }}&text={{ urlencode($post->title) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-full border border-[#210853]/15 bg-[#210853]/5 px-4 py-2 text-sm font-bold text-[#210853] transition hover:bg-[#210853]/10">
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.261 5.636 5.903-5.636zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            Twitter / X
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . $canonicalUrl) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-full border border-green-200 bg-green-50 px-4 py-2 text-sm font-bold text-green-700 transition hover:bg-green-100">
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M20.52 3.48A11.8 11.8 0 0 0 12.06 0C5.57 0 .29 5.28.29 11.77c0 2.07.54 4.09 1.57 5.87L0 24l6.54-1.71a11.7 11.7 0 0 0 5.52 1.41h.01c6.49 0 11.77-5.28 11.77-11.77 0-3.14-1.22-6.09-3.32-8.45Z"/></svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="w-full shrink-0 space-y-6 lg:w-72">

                {{-- Artículos relacionados --}}
                @if($related->isNotEmpty())
                    <div class="rounded-[2rem] border border-brand/10 bg-white p-6 shadow-sm lg:sticky lg:top-24">
                        <h3 class="mb-5 text-xs font-extrabold uppercase tracking-[0.28em] text-[#9e92b8]">Artículos relacionados</h3>
                        <div class="space-y-5">
                            @foreach($related as $r)
                                <div class="flex gap-3">
                                    @if($r->featured_image)
                                        <a href="{{ route('blog.show', $r->slug) }}" class="shrink-0">
                                            <img src="{{ Str::startsWith($r->featured_image, 'http') ? $r->featured_image : Storage::url($r->featured_image) }}"
                                                 alt="{{ $r->title }}"
                                                 class="h-14 w-20 rounded-xl object-cover"
                                                 loading="lazy">
                                        </a>
                                    @endif
                                    <div class="min-w-0">
                                        <a href="{{ route('blog.show', $r->slug) }}"
                                           class="line-clamp-2 text-sm font-bold leading-snug text-[#210853] transition hover:text-brand">
                                            {{ $r->title }}
                                        </a>
                                        <p class="mt-1 text-xs text-[#9e92b8]">{{ optional($r->published_at)->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- CTA --}}
                <div class="overflow-hidden rounded-[2rem] p-6 text-white shadow-glow"
                     style="background: linear-gradient(135deg, #9d2cf3 0%, #7457ff 50%, #1ca9ff 100%);">
                    <span class="material-symbols-outlined text-4xl text-white/70">phone_in_talk</span>
                    <h3 class="mt-3 text-base font-extrabold">Voice Bot con IA</h3>
                    <p class="mt-2 text-sm leading-6 text-white/80">Automatizá tu atención telefónica con inteligencia artificial generativa.</p>
                    <a href="https://cytcomunicaciones.com/voice-bot.html"
                       class="mt-4 inline-flex items-center gap-1 rounded-full border border-white/30 bg-white/15 px-4 py-2 text-sm font-extrabold text-white transition hover:bg-white/25">
                        Ver Voice Bot ↗
                    </a>
                </div>

                {{-- Categorías --}}
                <div class="rounded-[2rem] border border-brand/10 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-xs font-extrabold uppercase tracking-[0.28em] text-[#9e92b8]">Categorías</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->categories as $cat)
                            <a href="{{ route('blog.category', $cat->slug) }}"
                               class="rounded-full bg-brand/8 px-3 py-1 text-xs font-bold text-brand transition hover:bg-brand/15"
                               style="background:rgba(123,63,242,0.08);">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

@auth
<a href="{{ route('filament.admin.resources.posts.edit', $post->slug) }}"
   title="Editar entrada"
   style="position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;align-items:center;gap:0.5rem;background:#7c3aed;color:#fff;font-size:13px;font-weight:700;padding:0.6rem 1.1rem;border-radius:9999px;box-shadow:0 4px 20px rgba(124,58,237,0.45);text-decoration:none;transition:background 150ms" onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
    Editar entrada
</a>
@endauth

@endsection
