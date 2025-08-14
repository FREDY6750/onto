@extends('layouts.app')

@section('content')
<div class="flex">

    <main class="flex-1 max-w-7xl mx-auto py-10 px-6">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-10 flex items-center gap-3">
            <i data-lucide="activity" class="text-blue-600 w-7 h-7"></i>
            Tableau de bord
        </h1>

        {{-- Cartes statistiques --}}
        @php
            // Valeurs par défaut
            $coursCount        = $coursCount        ?? 0;
            $riviereCount      = $riviereCount      ?? 0; // NEW
            $affluentCount     = $affluentCount     ?? 0;
            $bassinCount       = $bassinCount       ?? 0;
            $localiteCount     = $localiteCount     ?? 0;
            $sbvNationalCount  = $sbvNationalCount  ?? 0; // Sous-bassins nationaux
            $sbvRegionalCount  = $sbvRegionalCount  ?? 0; // Sous-bassins régionaux

            $cards = [
                ['title' => "Cours d’eau",             'count' => $coursCount,       'color' => 'blue',    'icon' => 'waves',     'route' => route('cours.index')],
                ['title' => 'Rivières',                'count' => $riviereCount,     'color' => 'sky',     'icon' => 'droplets',  'route' => route('rivieres.index')], // NEW
                ['title' => 'Affluents',               'count' => $affluentCount,    'color' => 'indigo',  'icon' => 'git-branch','route' => route('affluents.index')],
                ['title' => 'Bassins versants',        'count' => $bassinCount,      'color' => 'cyan',    'icon' => 'mountain',  'route' => route('bassins.index')],
                ['title' => 'Localités',               'count' => $localiteCount,    'color' => 'amber',   'icon' => 'map-pin',   'route' => route('localites.index')],
                ['title' => 'SBV nationaux',           'count' => $sbvNationalCount, 'color' => 'emerald', 'icon' => 'layers',    'route' => route('sbvnationaux.index')],
                ['title' => 'SBV régionaux',           'count' => $sbvRegionalCount, 'color' => 'teal',    'icon' => 'layers-3',  'route' => route('sbvregionaux.index')],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-8 gap-6 mb-14">
            @foreach ($cards as $i => $card)
                <div class="bg-white border-l-4 border-{{ $card['color'] }}-500 rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-1 duration-200 p-5">
                    <div class="flex items-center justify-between">
                        <div class="text-left">
                            <p class="text-sm text-gray-500 mb-1">{{ $card['title'] }}</p>
                            <h2 class="text-3xl font-bold text-{{ $card['color'] }}-600" id="count-{{ $i }}">0</h2>
                        </div>
                        <div class="flex items-center justify-center bg-{{ $card['color'] }}-100 text-{{ $card['color'] }}-600 rounded-full w-12 h-12">
                            <i data-lucide="{{ $card['icon'] }}" class="w-6 h-6"></i>
                        </div>
                    </div>
                    <a href="{{ $card['route'] }}" class="block mt-4 text-sm text-{{ $card['color'] }}-600 hover:underline">
                        Voir les détails →
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Graphique Chart.js --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center gap-2">
                <i data-lucide="bar-chart-2" class="w-6 h-6 text-indigo-500"></i>
                Répartition visuelle des entités
            </h3>
            <canvas id="dashboardChart" height="200"></canvas>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Stats dans l'ordre des cartes ci-dessus
    const stats = [
        {{ $coursCount }},
        {{ $riviereCount }},       // NEW
        {{ $affluentCount }},
        {{ $bassinCount }},
        {{ $localiteCount }},
        {{ $sbvNationalCount }},
        {{ $sbvRegionalCount }},
    ];

    // Anime les compteurs
    stats.forEach((value, i) => {
        const el = document.getElementById(`count-${i}`);
        if (!el) return;
        animateCount(el, Number(value) || 0);
    });

    function animateCount(el, end) {
        let start = 0;
        const duration = 900; // ms
        const step = Math.max(1, Math.ceil((end || 0) / (duration / 30)));
        const interval = setInterval(() => {
            start += step;
            if (start >= end) {
                el.textContent = end;
                clearInterval(interval);
            } else {
                el.textContent = start;
            }
        }, 30);
    }

    // Chart.js
    const ctx = document.getElementById('dashboardChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 200);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.7)'); // blue-500
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.08)');

    const labels = [
        "Cours d'eau",
        "Rivières",             // NEW
        "Affluents",
        "Bassins versants",
        "Localités",
        "BV nationaux",         // NEW
        "SBV nationaux",
        "SBV régionaux",
    ];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Total',
                data: stats,
                backgroundColor: gradient,
                borderColor: '#3B82F6',
                borderWidth: 2,
                borderRadius: 10,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    titleColor: '#fff',
                    bodyColor: '#fff'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: '#e5e7eb' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endpush
