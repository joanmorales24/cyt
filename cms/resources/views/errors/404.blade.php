<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada | CyT Comunicaciones</title>
    <meta name="robots" content="noindex, follow">
    @vite(['resources/css/site.css'])
</head>
<body class="min-h-screen flex items-center justify-center bg-[#09051c] text-white font-sans px-6">
    <div class="text-center max-w-lg">
        <p class="text-sm font-bold uppercase tracking-[0.24em] text-[#8de4ff] mb-4">Error 404</p>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Esta página no existe</h1>
        <p class="text-[#c5c0e0] mb-10">
            El contenido que buscás pudo haberse movido o ya no está disponible.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}"
               class="rounded-full px-6 py-3 font-bold text-white transition hover:scale-[1.02]"
               style="background:linear-gradient(90deg,#9d2cf3 0%,#7457ff 45%,#1ca9ff 100%)">
                Ir al inicio
            </a>
            <a href="{{ route('blog.index') }}"
               class="rounded-full px-6 py-3 font-bold border border-white/15 text-white transition hover:bg-white/5">
                Ver el blog
            </a>
        </div>
    </div>
</body>
</html>
