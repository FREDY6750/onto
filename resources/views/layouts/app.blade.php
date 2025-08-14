<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', "Gestion des Cours d'Eau")</title>

    {{-- Tailwind CSS (CDN) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    {{-- Chart.js (si besoin dans certaines pages) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji"; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

<div x-data="{ open: false }" class="flex h-screen overflow-hidden" x-cloak>
    {{-- Sidebar --}}
    <aside
        :class="open ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed md:static z-40 inset-y-0 left-0 w-64 bg-white shadow-lg flex flex-col p-6 transform transition-transform duration-200 ease-out">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-blue-600 flex items-center gap-2">
                <i data-lucide="waves" class="w-6 h-6"></i>
                Hydrologie
            </h2>
            <button class="md:hidden rounded p-2 hover:bg-gray-100" @click="open = false" aria-label="Fermer le menu">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        @php
            // Petite fonction pour gérer les classes actives
            function nav_classes($isActive) {
                return $isActive
                    ? 'flex items-center gap-2 px-4 py-2 rounded-md bg-blue-50 text-blue-700'
                    : 'flex items-center gap-2 px-4 py-2 rounded-md hover:bg-blue-50 text-gray-700';
            }
        @endphp

        <nav class="space-y-2 text-sm font-medium">
            <a href="{{ route('dashboard') }}"
               class="{{ nav_classes(request()->routeIs('dashboard')) }}"
               @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
            </a>

            <a href="{{ route('cours.index') }}"
               class="{{ nav_classes(request()->routeIs('cours.*')) }}"
               @if(request()->routeIs('cours.*')) aria-current="page" @endif>
                <i data-lucide="waves" class="w-5 h-5"></i> Fleuves
            </a>

            <a href="{{ route('affluents.index') }}"
               class="{{ nav_classes(request()->routeIs('affluents.*')) }}"
               @if(request()->routeIs('affluents.*')) aria-current="page" @endif>
                <i data-lucide="arrow-big-down" class="w-5 h-5"></i> Affluents
            </a>

            <a href="{{ route('bassins.index') }}"
               class="{{ nav_classes(request()->routeIs('bassins.*')) }}"
               @if(request()->routeIs('bassins.*')) aria-current="page" @endif>
                <i data-lucide="mountain" class="w-5 h-5"></i> Bassins versants
            </a>

            {{-- Nationaux --}}
            <a href="{{ route('sbvnationaux.index') }}"
               class="{{ nav_classes(request()->routeIs('sbvnationaux.*')) }}"
               @if(request()->routeIs('sbvnationaux.*')) aria-current="page" @endif>
                <i data-lucide="layers" class="w-5 h-5"></i> Sous-bassins nationaux
            </a>

            {{-- Régionaux --}}
            <a href="{{ route('sbvregionaux.index') }}"
               class="{{ nav_classes(request()->routeIs('sbvregionaux.*')) }}"
               @if(request()->routeIs('sbvregionaux.*')) aria-current="page" @endif>
                <i data-lucide="layers-3" class="w-5 h-5"></i> Sous-bassins régionaux
            </a>

            <a href="{{ route('localites.index') }}"
               class="{{ nav_classes(request()->routeIs('localites.*')) }}"
               @if(request()->routeIs('localites.*')) aria-current="page" @endif>
                <i data-lucide="map-pin" class="w-5 h-5"></i> Localités
            </a>

           {{-- Rivières --}}
            <a href="{{ route('rivieres.index') }}"
            class="{{ nav_classes(request()->routeIs('rivieres.*')) }}"
            @if(request()->routeIs('rivieres.*')) aria-current="page" @endif>
                <i data-lucide="waves" class="w-5 h-5"></i> Rivières
            </a>

            {{-- Bassins versants nationaux --}}
            <a href=""
            class="{{ nav_classes(request()->routeIs('bvnationaux.*')) }}"
            @if(request()->routeIs('bvnationaux.*')) aria-current="page" @endif>
                <i data-lucide="mountain" class="w-5 h-5"></i> Bassins versants nationaux
            </a>

        </nav>
    </aside>

    {{-- Overlay mobile --}}
    <div
        x-show="open"
        class="fixed inset-0 bg-black/30 z-30 md:hidden"
        @click="open = false"
        aria-hidden="true"></div>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Topbar --}}
        <header class="sticky top-0 z-20 bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center gap-3">
                <button class="md:hidden rounded p-2 hover:bg-gray-100" @click="open = true" aria-label="Ouvrir le menu">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div class="text-sm text-gray-500">
                    @yield('breadcrumb')
                </div>
                <div class="ml-auto"></div>
            </div>
        </header>

        {{-- Contenu --}}
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            @yield('content')
        </main>
    </div>
</div>

{{-- Lucide init + Alpine (mini) pour le toggle --}}
<script>
    // Icônes
    lucide.createIcons();
</script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@stack('scripts')
</body>
</html>
