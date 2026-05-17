@extends('layouts.blog')

@section('seo_title', $category->name . ' | Blog | CYT Comunicaciones')
@section('seo_description', 'Artículos en la categoría ' . $category->name . ' - Blog de CYT Comunicaciones.')
@section('seo_canonical_tag')
<link rel="canonical" href="{{ route('blog.category', $category->slug) }}">
@endsection

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden px-6 py-20"
         style="background: radial-gradient(circle at top left, rgba(123,63,242,0.45), transparent 35%), radial-gradient(circle at 80% 20%, rgba(28,169,255,0.28), transparent 24%), linear-gradient(135deg, #1b0d44 0%, #0a0b25 48%, #080414 100%);">
    <div class="absolute inset-0 opacity-40"
         style="background-image: linear-gradient(rgba(255,255,255,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 44px 44px;"></div>
    <div class="relative mx-auto max-w-7xl">
        <nav class="mb-5 flex flex-wrap items-center gap-1.5 text-xs font-semibold text-muted/70">
            <a href="{{ route('blog.index') }}" class="hover:text-white transition">Blog</a>
            <span class="text-muted/40">/</span>
            <span class="text-muted/60">{{ $category->name }}</span>
        </nav>
        <span class="mb-5 inline-flex rounded-full border border-brandSoft/30 bg-brandSoft/10 px-4 py-2 text-xs font-extrabold uppercase tracking-[0.28em] text-cyan">
            Categoría
        </span>
        <h1 class="mt-4 max-w-2xl text-4xl font-extrabold leading-tight tracking-[-0.03em] text-white md:text-5xl">
            {{ $category->name }}
        </h1>
        @if($category->description)
            <p class="mt-4 max-w-xl text-lg leading-8 text-muted">{{ $category->description }}</p>
        @endif
    </div>
</section>

{{-- Contenido --}}
<div class="bg-[#fdf7ff] px-6 py-14">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col gap-10 lg:flex-row">

            {{-- Posts grid --}}
            <div class="min-w-0 flex-1">
                @if($posts->isEmpty())
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <span class="material-symbols-outlined text-6xl text-brand/20">category</span>
                        <p class="mt-4 text-lg font-semibold text-[#5a4e6e]">No hay artículos en esta categoría.</p>
                        <a href="{{ route('blog.index') }}" class="mt-4 text-sm font-bold text-brand hover:underline">Ver todos</a>
                    </div>
                @else
                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($posts as $post)
                            <article class="group flex flex-col overflow-hidden rounded-[2rem] border border-brand/10 bg-white shadow-sm transition hover:shadow-[0_8px_40px_rgba(123,63,242,0.12)] hover:-translate-y-0.5">
                                @if($post->featured_image)
                                    <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden">
                                        <img src="{{ Str::startsWith($post->featured_image, 'http') ? $post->featured_image : Storage::url($post->featured_image) }}"
                                             alt="{{ $post->featured_image_alt ?? $post->title }}"
                                             class="h-48 w-full object-cover transition duration-500 group-hover:scale-105"
                                             loading="lazy">
                                    </a>
                                @else
                                    <div class="h-48 w-full flex items-center justify-center"
                                         style="background: linear-gradient(135deg, rgba(123,63,242,0.12), rgba(28,169,255,0.08));">
                                        <span class="material-symbols-outlined text-5xl text-brand/20">article</span>
                                    </div>
                                @endif

                                <div class="flex flex-1 flex-col p-6">
                                    @if($post->categories->isNotEmpty())
                                        <div class="mb-3 flex flex-wrap gap-1.5">
                                            @foreach($post->categories as $cat)
                                                <a href="{{ route('blog.category', $cat->slug) }}"
                                                   class="rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wide text-brand transition hover:bg-brand/15 {{ $cat->slug === $category->slug ? 'bg-brand/15' : '' }}"
                                                   style="{{ $cat->slug !== $category->slug ? 'background:rgba(123,63,242,0.08);' : '' }}">
                                                    {{ $cat->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <h2 class="flex-1 text-base font-extrabold leading-snug tracking-tight text-[#210853]">
                                        <a href="{{ route('blog.show', $post->slug) }}"
                                           class="transition hover:text-brand">
                                            {{ $post->title }}
                                        </a>
                                    </h2>

                                    @if($post->excerpt)
                                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-[#5a4e6e]">
                                            {{ Str::limit(strip_tags($post->excerpt), 120) }}
                                        </p>
                                    @endif

                                    <div class="mt-4 flex items-center justify-between">
                                        <time class="text-xs font-semibold text-[#9e92b8]" datetime="{{ optional($post->published_at)->toIso8601String() }}">
                                            {{ optional($post->published_at)->format('d M Y') }}
                                        </time>
                                        <a href="{{ route('blog.show', $post->slug) }}"
                                           class="flex items-center gap-1 text-xs font-bold text-brand transition hover:gap-2">
                                            Leer más
                                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Paginación --}}
                    @if($posts->hasPages())
                        <div class="mt-10 flex items-center justify-center gap-2">
                            @if($posts->onFirstPage())
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-brand/10 text-sm text-[#9e92b8]">‹</span>
                            @else
                                <a href="{{ $posts->previousPageUrl() }}"
                                   class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-brand/20 text-sm text-brand transition hover:bg-brand/10">‹</a>
                            @endif

                            @foreach($posts->getUrlRange(max(1,$posts->currentPage()-2), min($posts->lastPage(),$posts->currentPage()+2)) as $page => $url)
                                @if($page == $posts->currentPage())
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full text-sm font-extrabold text-white"
                                          style="background: linear-gradient(90deg, #9d2cf3 0%, #1ca9ff 100%);">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-brand/20 text-sm font-semibold text-brand transition hover:bg-brand/10">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if($posts->hasMorePages())
                                <a href="{{ $posts->nextPageUrl() }}"
                                   class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-brand/20 text-sm text-brand transition hover:bg-brand/10">›</a>
                            @else
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-brand/10 text-sm text-[#9e92b8]">›</span>
                            @endif
                        </div>
                    @endif
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="w-full shrink-0 space-y-6 lg:w-64">

                {{-- Categorías --}}
                @if($categories->isNotEmpty())
                    <div class="rounded-[2rem] border border-brand/10 bg-white p-6 shadow-sm">
                        <h3 class="mb-4 text-xs font-extrabold uppercase tracking-[0.28em] text-[#9e92b8]">Categorías</h3>
                        <ul class="space-y-1">
                            @foreach($categories as $cat)
                                @if($cat->posts_count > 0)
                                    <li>
                                        <a href="{{ route('blog.category', $cat->slug) }}"
                                           class="flex items-center justify-between rounded-xl px-3 py-2 text-sm transition hover:bg-brand/5 {{ $cat->slug === $category->slug ? 'font-extrabold text-brand bg-brand/5' : 'text-[#3d2e5a]' }}">
                                            <span>{{ $cat->name }}</span>
                                            <span class="rounded-full bg-brand/10 px-2 py-0.5 text-xs font-bold text-brand">{{ $cat->posts_count }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- CTA --}}
                <div class="overflow-hidden rounded-[2rem] p-6 text-white shadow-glow"
                     style="background: linear-gradient(135deg, #9d2cf3 0%, #7457ff 50%, #1ca9ff 100%);">
                    <span class="material-symbols-outlined text-4xl text-white/70">support_agent</span>
                    <h3 class="mt-3 text-lg font-extrabold">¿Querés saber más?</h3>
                    <p class="mt-2 text-sm leading-6 text-white/80">Solicitá una demo de Orion Contact Center o Voice Bot con IA.</p>
                    <a href="https://cytcomunicaciones.com#contacto"
                       class="mt-4 inline-flex items-center gap-1 rounded-full border border-white/30 bg-white/15 px-4 py-2 text-sm font-extrabold text-white transition hover:bg-white/25">
                        Solicitar demo
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
