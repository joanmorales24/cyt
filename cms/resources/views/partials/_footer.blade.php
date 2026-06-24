{{--
  Footer unificado. Uso:
  @include('partials._footer', ['footerPage' => 'home'])      → anchors locales
  @include('partials._footer', ['footerPage' => 'voice-bot']) → anchors con /#
  @include('partials._footer')                                → anchors con /#
--}}
@php
  $fp   = $footerPage ?? 'other';
  $base = $fp === 'home' ? '' : '/';  // anchors locales en home, absolutos en el resto
@endphp

<style>
  footer.light-footer .social-chip {
    background: rgba(33,8,83,0.07);
    color: #210853;
    border-color: rgba(33,8,83,0.15);
  }
  footer.light-footer .social-chip:hover {
    background: rgba(123,63,242,0.15);
    color: #6713e1;
  }
  footer.light-footer .social-chip svg { fill: currentColor; }
</style>

<footer class="light-footer border-t px-6 py-16">
  <div class="mx-auto max-w-7xl">
    <div class="grid grid-cols-2 gap-12 md:grid-cols-4 lg:grid-cols-5">

      {{-- Marca + redes + ISO --}}
      <div class="col-span-2">
        <img alt="CYT Comunicaciones" class="h-10 w-auto" src="/img/logo.png" />
        <p class="mt-4 max-w-xs text-sm leading-7 text-muted">
          Más de 35 años transformando contact centers en LATAM con tecnología propia.
          Orion Contact Center e INTEGRA CRM.
        </p>
        <div class="mt-6 inline-flex items-center gap-3 rounded-2xl border border-[rgba(0,119,204,0.25)] bg-[rgba(0,119,204,0.07)] px-4 py-3">
          <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-[rgba(0,119,204,0.12)]">
            <span class="material-symbols-outlined text-lg" style="color:#0077cc;font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 24">verified</span>
          </div>
          <div>
            <p class="text-xs font-extrabold leading-tight" style="color:#0055a0">ISO 9001:2015</p>
          </div>
        </div>
        <div class="mt-6 flex gap-3">
          {{-- LinkedIn --}}
          <a href="https://www.linkedin.com/company/cyt-comunicaciones/" class="social-chip" aria-label="LinkedIn" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.13 1.45-2.13 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.07 2.07 0 1 1 0-4.14 2.07 2.07 0 0 1 0 4.14zm1.78 13.02H3.56V9h3.56v11.45zM22.22 0H1.77C.8 0 0 .77 0 1.73v20.54C0 23.23.8 24 1.77 24h20.45C23.2 24 24 23.23 24 22.27V1.73C24 .77 23.2 0 22.22 0z"/></svg>
          </a>
          {{-- Facebook --}}
          <a href="https://www.facebook.com/cytcomunicaciones/" class="social-chip" aria-label="Facebook" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
          </a>
          {{-- Instagram --}}
          <a href="https://www.instagram.com/cytcomunicaciones/" class="social-chip" aria-label="Instagram" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
          </a>
          {{-- YouTube --}}
          <a href="https://www.youtube.com/@cytcomunicaciones" class="social-chip" aria-label="YouTube" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
          </a>
        </div>
      </div>

      {{-- Soluciones --}}
      <div>
        <h6 class="mb-6 text-xs font-bold uppercase tracking-[0.28em] text-muted">Soluciones</h6>
        <ul class="space-y-4">
          <li><a class="text-sm text-muted transition-colors hover:text-brand" href="/voice-bot">Voice Bot con IA</a></li>
          <li><a class="text-sm text-muted transition-colors hover:text-brand" href="{{ $base }}#cx">Automatización CX</a></li>
          <li><a class="text-sm text-muted transition-colors hover:text-brand" href="{{ $base }}#plataforma">Orion Contact Center</a></li>
          <li><a class="text-sm text-muted transition-colors hover:text-brand" href="{{ $base }}#plataforma">INTEGRA CRM</a></li>
          <li><a class="text-sm text-muted transition-colors hover:text-brand" href="{{ $base }}#canales">Omnicanalidad</a></li>
        </ul>
      </div>

      {{-- Empresa --}}
      <div>
        <h6 class="mb-6 text-xs font-bold uppercase tracking-[0.28em] text-muted">Empresa</h6>
        <ul class="space-y-4">
          <li><a class="text-sm text-muted transition-colors hover:text-brand" href="{{ $base }}#nosotros">Nosotros</a></li>
          <li><a class="text-sm text-muted transition-colors hover:text-brand" href="{{ $base }}#industrias">Industrias</a></li>
          <li><a class="text-sm text-muted transition-colors hover:text-brand" href="{{ $base }}#premios">Premios</a></li>
          <li><a class="text-sm text-muted transition-colors hover:text-brand" href="{{ $base }}#resultados">Resultados</a></li>
          <li><a class="text-sm text-muted transition-colors hover:text-brand" href="/blog">Blog</a></li>
        </ul>
      </div>

      {{-- Contacto --}}
      <div>
        <h6 class="mb-6 text-xs font-bold uppercase tracking-[0.28em] text-muted">Contacto</h6>
        <ul class="space-y-3 text-sm text-muted">
          <li class="flex items-start gap-2">
            <span class="material-symbols-outlined mt-0.5 text-base flex-shrink-0">phone</span>
            <a href="tel:+541148313030" class="hover:text-brand transition-colors">54 11 4831-3030</a>
          </li>
          <li class="flex items-start gap-2">
            <span class="material-symbols-outlined mt-0.5 text-base flex-shrink-0">smartphone</span>
            <a href="https://wa.me/5491176602200" class="hover:text-brand transition-colors" target="_blank" rel="noopener">+54 9 11 7660-2200</a>
          </li>
          <li class="flex items-start gap-2">
            <span class="material-symbols-outlined mt-0.5 text-base flex-shrink-0">mail</span>
            <a href="mailto:info@cytcomunicaciones.com.ar" class="hover:text-brand transition-colors break-all">info@cytcomunicaciones.com.ar</a>
          </li>
          <li class="flex items-start gap-2">
            <span class="material-symbols-outlined mt-0.5 text-base flex-shrink-0">location_on</span>
            <span>Av. Federico Lacroze 3335, Entre Piso B · CABA · Argentina</span>
          </li>
        </ul>
      </div>

    </div>

    <div class="mt-16 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 text-center md:flex-row md:text-left">
      <p class="text-sm text-muted">© {{ date('Y') }} CYT Comunicaciones. Todos los derechos reservados.</p>
      <div class="flex flex-wrap justify-center gap-6 text-sm text-muted">
        <a class="transition-colors hover:text-brand" href="/politica-de-privacidad">Privacidad</a>
        <a class="transition-colors hover:text-brand" href="/terminos-y-condiciones">Términos</a>
        <a class="transition-colors hover:text-brand" href="/politica-de-cookies">Cookies</a>
        <a class="transition-colors hover:text-brand" href="/politica-de-calidad">Calidad</a>
      </div>
    </div>
  </div>
</footer>
