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
    @if(trim($__env->yieldContent('og_image')))
    <meta name="twitter:image" content="@yield('og_image')">
    @endif

    {{-- Structured data --}}
    @yield('structured_data')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,500,0,0&icon_names=arrow_back,arrow_forward,article,automation,bolt,calendar_today,call,category,chat,check_circle,close,cloud,download,forum,groups,health_and_safety,hub,integration_instructions,label_off,location_on,mail,menu,monetization_on,person_check,phone,phone_in_talk,public,query_stats,record_voice_over,route,savings,schedule,school,search_off,smart_toy,smartphone,storefront,support_agent,timer,trending_up,verified,visibility_off,workspace_premium&display=block" rel="stylesheet">
    @vite(['resources/css/site.css'])

    <style>
      /* Variantes propias del blog (siempre fondo claro, a diferencia del
         home que arranca sobre un hero oscuro): mismo nombre de clase que
         site.css pero distintos valores, así que se declaran acá para ganar
         por orden de cascada. */
      .text-gradient-dark {
        background: linear-gradient(90deg, #7b3ff2 0%, #1ca9ff 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
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
      .cyt-prose strong,
      .cyt-prose b { color: #0a0420; font-weight: 800; }
      .cyt-prose table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; font-size: 0.9rem; }
      .cyt-prose th { background: rgba(123,63,242,0.08); color: #210853; font-weight: 700; padding: 0.6rem 1rem; text-align: left; border-bottom: 2px solid rgba(123,63,242,0.2); }
      .cyt-prose td { padding: 0.6rem 1rem; border-bottom: 1px solid rgba(113,42,236,0.08); color: #3d2e5a; }
      .cyt-prose pre { background: #1b0d44; color: #e0d8ff; padding: 1.25rem; border-radius: 0.75rem; overflow-x: auto; font-size: 0.85rem; margin: 1.5rem 0; }
      .cyt-prose code { font-family: monospace; font-size: 0.875em; background: rgba(123,63,242,0.08); color: #7b3ff2; padding: 0.1em 0.4em; border-radius: 0.25rem; }
      .cyt-prose pre code { background: none; color: inherit; padding: 0; }
    </style>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
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
            <label class="grid gap-2 text-sm font-semibold">
                Mensaje
                <textarea name="mensaje" placeholder="¿En qué podemos ayudarte?" rows="4"
                          class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:border-purple-400 resize-none"></textarea>
            </label>
            <div
                class="cf-turnstile"
                data-sitekey="{{ config('services.turnstile.site') }}"
                data-size="invisible"
                data-execution="execute"
            ></div>
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

        try {
            const token = await new Promise((resolve, reject) => {
                const widget = form.querySelector('.cf-turnstile');
                if (!widget || typeof turnstile === 'undefined') {
                    reject(new Error('Turnstile no disponible'));
                    return;
                }
                turnstile.execute(widget, {
                    callback: (token) => resolve(token),
                    'error-callback': () => reject(new Error('Turnstile validation failed')),
                });
            });

            const data = {
                name:    form.nombre.value,
                email:   form.email.value,
                phone:   form.telefono.value,
                company: form.empresa.value,
                message: form.mensaje.value,
                source:  'blog',
                'cf-turnstile-response': token,
            };

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
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" class="h-6 w-6 text-white" aria-hidden="true"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224-99.6 224-222 0-59.3-25.2-115-67-157.1zM223.9 438.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.5-186.6 184.5zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
</a>

</body>
</html>
