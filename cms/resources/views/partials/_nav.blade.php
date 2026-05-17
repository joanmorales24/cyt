{{--
    Nav unificado. Uso:
    @include('partials._nav', ['active' => 'home'])   → para index
    @include('partials._nav', ['active' => 'voice-bot'])
    @include('partials._nav', ['active' => 'blog'])
--}}
@php $active = $active ?? ''; @endphp

<nav class="light-nav fixed top-0 z-50 w-full border-b backdrop-blur-xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
        <a href="/" class="flex items-center gap-3">
            <img alt="CYT Comunicaciones" class="h-9 w-auto" src="/img/logo.png" />
        </a>

        {{-- Desktop --}}
        <div class="hidden items-center gap-8 text-sm font-semibold md:flex">
            <a class="nav-link {{ $active === 'home' ? 'nav-link-active' : '' }}" href="/#plataforma">Plataforma</a>
            <a class="nav-link {{ $active === 'home' ? 'nav-link-active' : '' }}" href="/#industrias">Industrias</a>
            <a class="nav-link {{ $active === 'home' ? 'nav-link-active' : '' }}" href="/#nosotros">Nosotros</a>
            <a class="nav-link {{ $active === 'blog' ? 'nav-link-active' : '' }}" href="/blog">Blog</a>
            <a class="nav-link font-extrabold {{ $active === 'voice-bot' ? 'nav-link-active' : '' }}"
               style="color:#0077cc;" href="/voice-bot">Voice Bot ↗</a>
            <a href="/#contacto"
               class="rounded-full bg-cta px-5 py-2.5 text-sm font-extrabold text-white shadow-glow transition hover:scale-[1.02]">
                Solicitar demo
            </a>
        </div>

        {{-- Hamburguesa mobile --}}
        <button id="mobile-menu-btn"
                class="md:hidden flex items-center justify-center w-10 h-10 rounded-full border border-brand/20 bg-brand/5"
                aria-label="Menú">
            <span class="material-symbols-outlined text-xl text-[#210853]">menu</span>
        </button>
    </div>

    {{-- Mobile dropdown --}}
    <div id="mobile-menu"
         class="hidden md:hidden border-t border-brand/10 bg-[#fdf7ff]/95 backdrop-blur-xl px-6 py-4 space-y-3">
        <a href="/#plataforma" class="block text-sm font-semibold text-[#4a4456] hover:text-[#210853] py-1">Plataforma</a>
        <a href="/#industrias" class="block text-sm font-semibold text-[#4a4456] hover:text-[#210853] py-1">Industrias</a>
        <a href="/#nosotros"   class="block text-sm font-semibold text-[#4a4456] hover:text-[#210853] py-1">Nosotros</a>
        <a href="/blog"        class="block text-sm font-semibold text-[#4a4456] hover:text-[#210853] py-1 {{ $active === 'blog' ? 'font-extrabold text-[#210853]' : '' }}">Blog</a>
        <a href="/voice-bot"   class="block text-sm font-extrabold py-1 {{ $active === 'voice-bot' ? 'text-[#0077cc]' : 'text-[#0077cc]' }}">Voice Bot ↗</a>
        <a href="/#contacto"
           class="block w-full text-center rounded-full bg-cta px-5 py-2.5 text-sm font-extrabold text-white shadow-glow mt-2">
            Solicitar demo
        </a>
    </div>
</nav>

<script>
    (function() {
        const btn  = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if (btn && menu) {
            btn.addEventListener('click', () => menu.classList.toggle('hidden'));
        }
    })();
</script>
