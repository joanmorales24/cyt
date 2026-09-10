<!doctype html>
<html class="scroll-smooth" lang="es">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'CYT Comunicaciones')</title>
    <meta name="description" content="@yield('description', 'CYT Comunicaciones')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    @vite(['resources/css/site.css'])
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
