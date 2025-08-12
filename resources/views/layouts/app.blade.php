<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion des Cours d\'Eau')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar (statique, toujours visible) -->
        <!-- Sidebar (statique, toujours visible) -->
<aside class="w-64 bg-white shadow-lg flex flex-col p-6">
    <h2 class="text-2xl font-bold text-blue-600 mb-6 flex items-center gap-2">
        <i data-lucide="waves" class="w-6 h-6"></i>
        Hydrologie
    </h2>
    <nav class="space-y-2 text-sm font-medium">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 rounded-md hover:bg-blue-100 text-blue-700 bg-blue-50">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
        </a>
        <a href="{{ route('cours.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-md hover:bg-blue-50">
            <i data-lucide="waves" class="w-5 h-5"></i> Cours d’eau
        </a>
        <a href="{{ route('affluents.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-md hover:bg-blue-50">
            <i data-lucide="arrow-big-down" class="w-5 h-5"></i> Affluents
        </a>
        <a href="{{ route('bassins.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-md hover:bg-blue-50">
            <i data-lucide="mountain" class="w-5 h-5"></i> Bassins versants
        </a>
        <a href="{{ route('localites.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-md hover:bg-blue-50">
            <i data-lucide="map-pin" class="w-5 h-5"></i> Localités
        </a>
    </nav>
</aside>


        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>

    <!-- Lucide Icon Init -->
    <script>
        lucide.createIcons();
    </script>

    @stack('scripts')
</body>
</html>
