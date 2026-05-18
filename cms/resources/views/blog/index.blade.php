@extends('layouts.blog')

@section('seo_title', 'Blog | CYT Comunicaciones')
@section('seo_description', 'Noticias, artículos y novedades sobre contact centers, omnicanalidad y CX en LATAM.')
@section('seo_canonical_tag')
<link rel="canonical" href="{{ route('blog.index') }}">
@endsection

@section('content')

{{-- Hero del blog --}}
<section class="relative overflow-hidden px-6 py-20"
         style="background: radial-gradient(circle at top left, rgba(123,63,242,0.45), transparent 35%), radial-gradient(circle at 80% 20%, rgba(28,169,255,0.28), transparent 24%), linear-gradient(135deg, #1b0d44 0%, #0a0b25 48%, #080414 100%);">
    {{-- Grid lines --}}
    <div class="absolute inset-0 opacity-40"
         style="background-image: linear-gradient(rgba(255,255,255,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 44px 44px;"></div>
    <div class="relative mx-auto max-w-7xl">
        <span class="mb-5 inline-flex rounded-full border border-brandSoft/30 bg-brandSoft/10 px-4 py-2 text-xs font-extrabold uppercase tracking-[0.28em] text-cyan">
            Noticias &amp; Conocimiento
        </span>
        <h1 class="max-w-2xl text-4xl font-extrabold leading-tight tracking-[-0.03em] text-white md:text-5xl">
            El blog de <span class="text-gradient">CYT Comunicaciones</span>
        </h1>
        <p class="mt-5 max-w-xl text-lg leading-8 text-muted">
            Artículos sobre contact centers, omnicanalidad, CX e inteligencia artificial para el sector empresarial en LATAM.
        </p>
        {{-- Search --}}
        <form method="GET" action="{{ route('blog.index') }}" class="mt-8 flex max-w-lg gap-2">
            <input type="search" name="q" value="{{ request('q') }}"
                   placeholder="Buscar artículos..."
                   class="flex-1 rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm text-white placeholder:text-muted/70 backdrop-blur focus:border-brandSoft/60 focus:outline-none focus:ring-0">
            <button type="submit"
                    class="rounded-full bg-cta px-6 py-3 text-sm font-extrabold text-white shadow-glow transition hover:scale-[1.02]">
                Buscar
            </button>
        </form>
    </div>
</section>

{{-- Contenido --}}
<div class="bg-[#fdf7ff] px-6 py-14">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col gap-10 lg:flex-row">

            {{-- Posts grid --}}
            <div class="min-w-0 flex-1">

                {{-- Filtro activo --}}
                @if(request('q'))
                    <div class="mb-6 flex items-center gap-3">
                        <span class="text-sm text-[#5a4e6e]">Resultados para:</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-brand/10 px-3 py-1 text-sm font-semibold text-brand">
                            "{{ request('q') }}"
                            <a href="{{ route('blog.index') }}" class="ml-1 text-brand/60 hover:text-brand">✕</a>
                        </span>
                    </div>
                @endif

                @if($posts->isEmpty())
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <span class="material-symbols-outlined text-6xl text-brand/20">search_off</span>
                        <p class="mt-4 text-lg font-semibold text-[#5a4e6e]">No se encontraron artículos.</p>
                        <a href="{{ route('blog.index') }}" class="mt-4 text-sm font-bold text-brand hover:underline">Ver todos</a>
                    </div>
                @else
                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($posts as $post)
                            <article class="group flex flex-col overflow-hidden rounded-[2rem] border border-brand/10 bg-white shadow-sm transition hover:shadow-[0_8px_40px_rgba(123,63,242,0.12)] hover:-translate-y-0.5">
                                {{-- Imagen --}}
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
                                    {{-- Categorías --}}
                                    @if($post->categories->isNotEmpty())
                                        <div class="mb-3 flex flex-wrap gap-1.5">
                                            @foreach($post->categories as $cat)
                                                <a href="{{ route('blog.category', $cat->slug) }}"
                                                   class="rounded-full bg-brand/8 px-2.5 py-0.5 text-xs font-bold uppercase tracking-wide text-brand transition hover:bg-brand/15"
                                                   style="background:rgba(123,63,242,0.08);">
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
                            {{-- Previous --}}
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

                            {{-- Next --}}
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
                                           class="flex items-center justify-between rounded-xl px-3 py-2 text-sm transition hover:bg-brand/5 {{ request('categoria') === $cat->slug ? 'font-extrabold text-brand bg-brand/5' : 'text-[#3d2e5a]' }}">
                                            <span>{{ $cat->name }}</span>
                                            <span class="rounded-full bg-brand/10 px-2 py-0.5 text-xs font-bold text-brand">{{ $cat->posts_count }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Etiquetas --}}
                @if($tags->isNotEmpty())
                    <div class="rounded-[2rem] border border-brand/10 bg-white p-6 shadow-sm">
                        <h3 class="mb-4 text-xs font-extrabold uppercase tracking-[0.28em] text-[#9e92b8]">Etiquetas</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tags->take(30) as $tag)
                                @if($tag->posts_count > 0)
                                    <a href="{{ route('blog.tag', $tag->slug) }}"
                                       class="rounded-full border border-brand/15 bg-brand/5 px-3 py-1 text-xs font-semibold text-brand transition hover:bg-brand/15">
                                        {{ $tag->name }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- CTA --}}
                <div class="overflow-hidden rounded-[2rem] p-6 text-white shadow-glow"
                     style="background: linear-gradient(135deg, #9d2cf3 0%, #7457ff 50%, #1ca9ff 100%);">
                    <span class="material-symbols-outlined text-4xl text-white/70">support_agent</span>
                    <h3 class="mt-3 text-lg font-extrabold">¿Querés saber más?</h3>
                    <p class="mt-2 text-sm leading-6 text-white/80">Solicitá una demo de Orion Contact Center o Voice Bot con IA.</p>
                    <button data-open-demo
                            class="mt-4 inline-flex items-center gap-1 rounded-full border border-white/30 bg-white/15 px-4 py-2 text-sm font-extrabold text-white transition hover:bg-white/25">
                        Solicitar demo
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
