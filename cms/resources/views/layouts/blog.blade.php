<!DOCTYPE html>
<html class="scroll-smooth" lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO --}}
    <title>@yield('seo_title', 'Blog | CYT Comunicaciones')</title>
    <meta name="description" content="@yield('seo_description', 'Noticias, novedades y artículos de CYT Comunicaciones.')">
    @if(trim($__env->yieldContent('seo_keywords')))
    <meta name="keywords" content="@yield('seo_keywords')">
    @endif
    @yield('seo_canonical_tag')

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('seo_title', 'Blog | CYT Comunicaciones')">
    <meta property="og:description" content="@yield('seo_description', 'Noticias, novedades y artículos de CYT Comunicaciones.')">
    <meta property="og:url" content="{{ url()->current() }}">
    @if(trim($__env->yieldContent('og_image')))
    <meta property="og:image" content="@yield('og_image')">
    @endif
    <meta property="og:site_name" content="CYT Comunicaciones">
    <meta property="og:locale" content="es_AR">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('seo_title', 'Blog | CYT Comunicaciones')">
    <meta name="twitter:description" content="@yield('seo_description', 'Noticias, novedades y artículos de CYT Comunicaciones.')">

    {{-- Structured data --}}
    @yield('structured_data')

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              base:        "#09051c",
              ink:         "#f8f7ff",
              muted:       "#c5c0e0",
              line:        "rgba(255,255,255,0.08)",
              brand:       "#7b3ff2",
              brandSoft:   "#aa7cff",
              cyan:        "#1ca9ff",
              cyanSoft:    "#8de4ff",
              panel:       "rgba(18, 14, 44, 0.72)",
              panelStrong: "rgba(13, 10, 34, 0.92)",
              accent:      "#72ffd2",
            },
            boxShadow: {
              glow: "0 24px 80px rgba(73, 43, 180, 0.45)",
              card: "0 18px 60px rgba(4, 7, 30, 0.35)",
            },
            backgroundImage: {
              hero: "radial-gradient(circle at top left, rgba(123,63,242,0.45), transparent 35%), radial-gradient(circle at 80% 20%, rgba(28,169,255,0.28), transparent 24%), linear-gradient(135deg, #1b0d44 0%, #0a0b25 48%, #080414 100%)",
              cta:  "linear-gradient(90deg, #9d2cf3 0%, #7457ff 45%, #1ca9ff 100%)",
            },
            fontFamily: { sans: ["Manrope", "sans-serif"] },
          },
        },
      };
    </script>

    <style>
      .material-symbols-outlined {
        font-variation-settings: "FILL" 0, "wght" 500, "GRAD" 0, "opsz" 24;
      }
      .glass {
        background: linear-gradient(180deg, rgba(255,255,255,0.12), rgba(255,255,255,0.05));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
      }
      .light-body .glass {
        background: rgba(255,255,255,0.88) !important;
        box-shadow: 0 4px 32px rgba(113,42,236,0.07);
      }
      .text-gradient {
        background: linear-gradient(90deg, #f8f7ff 0%, #b79aff 36%, #5ebeff 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
      }
      .text-gradient-dark {
        background: linear-gradient(90deg, #7b3ff2 0%, #1ca9ff 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
      }
      nav.light-nav {
        background: rgba(253,247,255,0.94) !important;
        border-color: rgba(113,42,236,0.1) !important;
      }
      .nav-link {
        position: relative;
        display: inline-flex;
        color: #4a4456;
        transition: color 150ms ease;
      }
      .nav-link:hover { color: #210853; }
      .nav-link-active {
        color: #210853;
        font-weight: 800;
      }
      .nav-link-active::after {
        content: "";
        position: absolute;
        left: 50%; bottom: -0.65rem;
        width: 1.75rem; height: 2px;
        transform: translateX(-50%);
        border-radius: 9999px;
        background: linear-gradient(90deg, #9d2cf3 0%, #1ca9ff 100%);
      }
      .social-chip {
        display: inline-flex; align-items: center; justify-content: center;
        width: 3rem; height: 3rem; border-radius: 9999px;
        border: 1px solid rgba(113,42,236,0.18);
        background: rgba(113,42,236,0.08);
        color: #210853;
      }
      .social-chip svg { width: 1.25rem; height: 1.25rem; fill: currentColor; }
      footer.light-footer {
        background: #f3eaff !important;
        border-color: rgba(113,42,236,0.12) !important;
        color: #210853;
      }
      footer.light-footer .text-muted { color: #5a4e6e !important; }

      /* Prose styles for post content */
      .cyt-prose img       { border-radius: 1rem; max-width: 100%; height: auto; margin: 1.5rem 0; box-shadow: 0 4px 24px rgba(113,42,236,0.1); }
      .cyt-prose a         { color: #7b3ff2; text-decoration: underline; }
      .cyt-prose a:hover   { color: #1ca9ff; }
      .cyt-prose h2        { font-size: 1.5rem; font-weight: 800; margin: 2rem 0 0.75rem; color: #210853; letter-spacing: -0.02em; }
      .cyt-prose h3        { font-size: 1.2rem; font-weight: 700; margin: 1.5rem 0 0.5rem; color: #210853; }
      .cyt-prose h4        { font-size: 1rem; font-weight: 700; margin: 1.25rem 0 0.5rem; color: #210853; }
      .cyt-prose p         { margin-bottom: 1.1rem; line-height: 1.85; color: #3d2e5a; }
      .cyt-prose ul        { list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; color: #3d2e5a; }
      .cyt-prose ol        { list-style: decimal; padding-left: 1.5rem; margin-bottom: 1rem; color: #3d2e5a; }
      .cyt-prose li        { margin-bottom: 0.4rem; line-height: 1.7; }
      .cyt-prose blockquote {
        border-left: 3px solid #7b3ff2; padding-left: 1.25rem;
        color: #5a4e6e; font-style: italic; margin: 1.5rem 0;
        background: rgba(123,63,242,0.04); border-radius: 0 0.5rem 0.5rem 0; padding: 1rem 1.25rem;
      }
      .cyt-prose strong { color: #210853; font-weight: 700; }
      .cyt-prose table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; font-size: 0.9rem; }
      .cyt-prose th { background: rgba(123,63,242,0.08); color: #210853; font-weight: 700; padding: 0.6rem 1rem; text-align: left; border-bottom: 2px solid rgba(123,63,242,0.2); }
      .cyt-prose td { padding: 0.6rem 1rem; border-bottom: 1px solid rgba(113,42,236,0.08); color: #3d2e5a; }
      .cyt-prose pre { background: #1b0d44; color: #e0d8ff; padding: 1.25rem; border-radius: 0.75rem; overflow-x: auto; font-size: 0.85rem; margin: 1.5rem 0; }
      .cyt-prose code { font-family: monospace; font-size: 0.875em; background: rgba(123,63,242,0.08); color: #7b3ff2; padding: 0.1em 0.4em; border-radius: 0.25rem; }
      .cyt-prose pre code { background: none; color: inherit; padding: 0; }
    </style>
</head>

<body class="bg-[#fdf7ff] font-sans text-[#210853] selection:bg-brand/40 selection:text-white">

{{-- Nav --}}
@include('partials._nav', ['active' => 'blog'])

{{-- Page content --}}
<main class="pt-[73px]">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="light-footer border-t px-6 py-16">
    <div class="mx-auto max-w-7xl">
        <div class="grid grid-cols-2 gap-12 md:grid-cols-4 lg:grid-cols-5">
            <div class="col-span-2">
                <img src="/img/logo.png" alt="CYT Comunicaciones" class="h-10 w-auto">
                <p class="mt-4 max-w-xs text-sm leading-7 text-muted">
                    Más de 35 años transformando contact centers en LATAM con tecnología propia.
                    Orion Contact Center e INTEGRA CRM.
                </p>
                <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-brand/10 bg-brand/5 px-4 py-2">
                    <span class="material-symbols-outlined text-base text-brand">verified</span>
                    <span class="text-xs font-bold text-muted">ISO 9001:2015 · Bureau Veritas</span>
                </div>
                <div class="mt-6 flex gap-3">
                    <a href="https://www.linkedin.com/company/cyt-comunicaciones" class="social-chip" aria-label="LinkedIn" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M20.45 20.45h-3.56v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.13 1.45-2.13 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.07 2.07 0 1 1 0-4.14 2.07 2.07 0 0 1 0 4.14zm1.78 13.02H3.56V9h3.56v11.45zM22.22 0H1.77C.8 0 0 .77 0 1.73v20.54C0 23.23.8 24 1.77 24h20.45C23.2 24 24 23.23 24 22.27V1.73C24 .77 23.2 0 22.22 0z"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div>
                <h6 class="mb-6 text-xs font-bold uppercase tracking-[0.28em] text-muted">Soluciones</h6>
                <ul class="space-y-4">
                    <li><a href="{{ route('voice-bot') }}" class="text-sm text-muted transition-colors hover:text-brand">Voice Bot con IA</a></li>
                    <li><a href="{{ route('home') }}#cx" class="text-sm text-muted transition-colors hover:text-brand">Automatización CX</a></li>
                    <li><a href="{{ route('home') }}#plataforma" class="text-sm text-muted transition-colors hover:text-brand">Orion Contact Center</a></li>
                    <li><a href="{{ route('home') }}#plataforma" class="text-sm text-muted transition-colors hover:text-brand">INTEGRA CRM</a></li>
                    <li><a href="{{ route('home') }}#canales" class="text-sm text-muted transition-colors hover:text-brand">Omnicanalidad</a></li>
                </ul>
            </div>
            <div>
                <h6 class="mb-6 text-xs font-bold uppercase tracking-[0.28em] text-muted">Blog</h6>
                <ul class="space-y-4">
                    <li><a href="{{ route('blog.index') }}" class="text-sm text-muted transition-colors hover:text-brand">Todas las entradas</a></li>
                    <li><a href="{{ route('blog.category', 'noticias') }}" class="text-sm text-muted transition-colors hover:text-brand">Noticias</a></li>
                    <li><a href="{{ route('blog.category', 'prensa') }}" class="text-sm text-muted transition-colors hover:text-brand">Prensa</a></li>
                    <li><a href="{{ route('sitemap') }}" class="text-sm text-muted transition-colors hover:text-brand">Sitemap</a></li>
                </ul>
            </div>
            <div>
                <h6 class="mb-6 text-xs font-bold uppercase tracking-[0.28em] text-muted">Contacto</h6>
                <ul class="space-y-4 text-sm text-muted">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined mt-0.5 text-base text-brand">language</span>
                        <span>www.cytcomunicaciones.com</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined mt-0.5 text-base text-brand">mail</span>
                        <a href="mailto:info@cytcomunicaciones.com.ar" class="hover:text-brand transition-colors">info@cytcomunicaciones.com.ar</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mt-16 flex flex-col items-center justify-between gap-4 border-t border-brand/10 pt-8 text-center md:flex-row md:text-left">
            <p class="text-sm text-muted">© {{ date('Y') }} CYT Comunicaciones. Todos los derechos reservados.</p>
            <div class="flex gap-6 text-sm text-muted">
                <a href="{{ route('sitemap') }}" class="transition-colors hover:text-brand">Sitemap</a>
                <a href="{{ route('home') }}" class="transition-colors hover:text-brand">Inicio</a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
