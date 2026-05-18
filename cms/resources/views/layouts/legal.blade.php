<!doctype html>
<html class="scroll-smooth" lang="es">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'CYT Comunicaciones')</title>
    <meta name="description" content="@yield('description', 'CYT Comunicaciones')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              base: "#09051c", ink: "#f8f7ff", muted: "#c5c0e0",
              brand: "#7b3ff2", brandSoft: "#aa7cff",
              cyan: "#1ca9ff", cyanSoft: "#8de4ff",
              accent: "#72ffd2",
            },
            boxShadow: { glow: "0 24px 80px rgba(73,43,180,0.45)" },
            backgroundImage: { cta: "linear-gradient(90deg,#9d2cf3 0%,#7457ff 45%,#1ca9ff 100%)" },
            fontFamily: { sans: ["Manrope", "sans-serif"] },
          },
        },
      };
    </script>
    <style>
      .material-symbols-outlined {
        font-family: 'Material Symbols Outlined';
        font-weight: normal; font-style: normal; font-size: inherit;
        line-height: 1; letter-spacing: normal; text-transform: none;
        display: inline-block; white-space: nowrap; direction: ltr;
        font-feature-settings: 'liga'; -webkit-font-feature-settings: 'liga';
        -webkit-font-smoothing: antialiased;
        font-variation-settings: "FILL" 0,"wght" 500,"GRAD" 0,"opsz" 24;
      }
      nav.light-nav { background: rgba(253,247,255,0.94) !important; border-color: rgba(113,42,236,0.1) !important; }
      nav.light-nav .nav-link { color: #4a4456 !important; }
      nav.light-nav .nav-link:hover { color: #210853 !important; }
      footer.light-footer { background: #f3eaff !important; border-color: rgba(113,42,236,0.12) !important; color: #210853; }
      footer.light-footer .text-muted { color: #5a4e6e !important; }
      .social-chip { display:inline-flex; align-items:center; justify-content:center; width:2.25rem; height:2.25rem; border-radius:9999px; border:1px solid rgba(113,42,236,0.18); background:rgba(113,42,236,0.08); color:#210853; transition:background 0.2s,color 0.2s; }
      .social-chip:hover { background:rgba(113,42,236,0.18); color:#7b3ff2; }
      .social-chip svg { width:1rem; height:1rem; fill:currentColor; }
      /* Prose legal */
      .legal-prose h2 { font-size:1.125rem; font-weight:700; color:#210853; margin-top:2rem; margin-bottom:.5rem; }
      .legal-prose p { color:#3d3350; line-height:1.75; margin-bottom:1rem; }
      .legal-prose ul { list-style:disc; padding-left:1.5rem; color:#3d3350; line-height:1.75; margin-bottom:1rem; }
      .legal-prose ul li { margin-bottom:.4rem; }
    </style>
  </head>
  <body class="bg-[#fdf7ff] font-sans text-[#210853]">

    @include('partials._nav', ['active' => ''])

    <main class="mx-auto max-w-4xl px-6 pb-24 pt-32">

      <div class="mb-10">
        <a href="/" class="inline-flex items-center gap-1 text-sm text-[#7b3ff2] hover:underline">
          <span class="material-symbols-outlined text-base">arrow_back</span> Inicio
        </a>
      </div>

      <article class="legal-prose">
        <h1 class="mb-2 text-3xl font-extrabold text-[#210853]">@yield('heading')</h1>
        @hasSection('last_update')
        <p class="mb-8 text-sm text-[#5a4e6e]">@yield('last_update')</p>
        @endif
        @yield('content')
      </article>
    </main>

    @include('partials._footer')

  </body>
</html>
