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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

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
      .cyt-prose strong { color: #0a0420; font-weight: 800; }
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
@include('partials._footer', ['footerPage' => 'blog'])

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
  <i class="fab fa-whatsapp text-white text-2xl"></i>
</a>

</body>
</html>
