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
                    <span class="text-xs font-bold text-muted">ISO 9001:2015</span>
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

{{-- ── Modal Solicitar Demo (mismo endpoint CRM que index/voice-bot) ── --}}
<div id="schedule-modal"
     class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 px-4 backdrop-blur-sm">
    <div class="w-full max-w-lg overflow-hidden rounded-[2rem] border border-white/10 bg-[#110b2c] shadow-2xl">
        <div class="flex items-center justify-between border-b border-white/10 px-6 py-5">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.24em] text-purple-300">Solicitar demo</p>
                <h3 class="mt-2 text-2xl font-extrabold text-white">Agendá una reunión con CYT</h3>
            </div>
            <button id="close-schedule-modal" type="button"
                    class="flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/5 text-2xl text-white transition hover:bg-white/10">×</button>
        </div>
        <form id="demo-form" class="grid gap-5 p-6 text-white">
            <label class="grid gap-2 text-sm font-semibold">
                Nombre
                <input name="nombre" type="text" required placeholder="Tu nombre"
                       class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:border-purple-400"/>
            </label>
            <label class="grid gap-2 text-sm font-semibold">
                Email
                <input name="email" type="email" placeholder="tu@empresa.com"
                       class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:border-purple-400"/>
            </label>
            <label class="grid gap-2 text-sm font-semibold">
                Teléfono
                <input name="telefono" type="tel" placeholder="+54 ..."
                       class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:border-purple-400"/>
            </label>
            <label class="grid gap-2 text-sm font-semibold">
                Empresa
                <input name="empresa" type="text" placeholder="Nombre de la empresa"
                       class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:border-purple-400"/>
            </label>
            <button type="submit"
                    class="rounded-full px-6 py-4 text-lg font-extrabold text-white transition hover:opacity-90"
                    style="background:linear-gradient(90deg,#9d2cf3 0%,#7457ff 50%,#1ca9ff 100%)">
                Quiero ver una demo
            </button>
        </form>
    </div>
</div>

<script>
(function () {
    const modal    = document.getElementById('schedule-modal');
    const closeBtn = document.getElementById('close-schedule-modal');
    if (!modal) return;

    function openModal()  { modal.classList.remove('hidden'); modal.classList.add('flex'); }
    function closeModal() { modal.classList.add('hidden');    modal.classList.remove('flex'); }

    document.querySelectorAll('[data-open-demo]').forEach(btn => btn.addEventListener('click', openModal));
    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    const form = document.getElementById('demo-form');
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = form.querySelector('[type="submit"]');
        const original = btn.textContent;
        btn.textContent = 'Enviando…';
        btn.disabled = true;
        form.querySelector('.form-error-msg')?.remove();

        const data = {
            name:    form.nombre.value,
            email:   form.email.value,
            phone:   form.telefono.value,
            company: form.empresa.value,
            source:  'blog',
        };

        try {
            const res = await fetch('{{ route("leads.store") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify(data),
            });
            if (res.status === 429) {
                btn.textContent = original; btn.disabled = false;
                btn.insertAdjacentHTML('afterend', '<p class="form-error-msg text-xs text-yellow-400 text-center mt-2">Demasiados intentos. Esperá unos minutos.</p>');
                return;
            }
            if (res.ok) {
                form.innerHTML = '<div class="py-10 text-center"><p class="text-xl font-extrabold text-white">¡Solicitud enviada!</p><p class="mt-2 text-sm text-white/70">Te contactamos en breve para coordinar la demo.</p></div>';
            } else {
                throw new Error('HTTP ' + res.status);
            }
        } catch (err) {
            btn.textContent = original; btn.disabled = false;
            btn.insertAdjacentHTML('afterend', '<p class="form-error-msg text-xs text-red-400 text-center mt-2">Error ' + (err.message || '') + '. Escribinos a <a href="mailto:info@cytcomunicaciones.com.ar" class="underline">info@cytcomunicaciones.com.ar</a></p>');
        }
    });
})();
</script>

{{-- WhatsApp Floating Button --}}
<a href="https://wa.me/5491176602200" target="_blank" rel="noopener" class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 bg-[#25D366] hover:bg-[#20BA5A] rounded-full shadow-lg transition-all hover:scale-110"
   title="Contactá por WhatsApp">
  <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-9.746 9.798c0 2.734.707 5.404 2.051 7.721L1.475 23.5l8.186-2.15c2.259 1.23 4.799 1.881 7.38 1.881 5.411 0 9.858-4.426 9.858-9.87 0-2.633-.704-5.403-2.049-7.721l2.15-8.186-8.186 2.15c-2.26-1.23-4.799-1.881-7.38-1.881m0-2C6.464 0 3.546 2.919 3.546 6.477 3.546 9.897 6.2 12.8 9.8 13.5c2.312 0 4.465-.899 6.067-2.501 1.602-1.602 2.501-3.755 2.501-6.067 0-2.558-.899-4.965-2.501-6.567C14.265.899 11.862 0 9.551 0Z"/>
  </svg>
</a>

</body>
</html>
