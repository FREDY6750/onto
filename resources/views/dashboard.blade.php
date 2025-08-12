@extends('layouts.app')

@section('content')
<div class="flex">

    <!-- Main content -->
    <main class="flex-1 max-w-7xl mx-auto py-10 px-6">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-10 flex items-center gap-3">
            <i class="lucide lucide-activity text-blue-600 text-3xl"></i> Tableau de bord
        </h1>

        <!-- Cartes statistiques -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-14">
            @php
                $cards = [
                    ['title' => 'Cours d’eau', 'count' => $coursCount, 'color' => 'blue', 'icon' => 'water', 'route' => route('cours.index')],
                    ['title' => 'Affluents', 'count' => $affluentCount, 'color' => 'green', 'icon' => 'flowing-water', 'route' => route('affluents.index')],
                    ['title' => 'Bassins versants', 'count' => $bassinCount, 'color' => 'purple', 'icon' => 'mountain', 'route' => route('bassins.index')],
                    ['title' => 'Localités', 'count' => $localiteCount, 'color' => 'orange', 'icon' => 'map-pin', 'route' => route('localites.index')],
                    ['title' => 'Villes', 'count' => $villeCount, 'color' => 'pink', 'icon' => 'building-2', 'route' => route('localites.index')],
                ];
            @endphp

            @foreach ($cards as $card)
            <div class="bg-white border-l-4 border-{{ $card['color'] }}-500 rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-1 duration-200 p-5">
                <div class="flex items-center justify-between">
                    <div class="text-left">
                        <p class="text-sm text-gray-500 mb-1">{{ $card['title'] }}</p>
                        <h2 class="text-3xl font-bold text-{{ $card['color'] }}-600" id="count-{{ $loop->index }}">0</h2>
                    </div>
                    <div class="flex items-center justify-center bg-{{ $card['color'] }}-100 text-{{ $card['color'] }}-600 rounded-full w-12 h-12">
                        <i class="lucide lucide-{{ $card['icon'] }} text-2xl"></i>
                    </div>
                </div>
                <a href="{{ $card['route'] }}" class="block mt-4 text-sm text-{{ $card['color'] }}-600 hover:underline">Voir les détails →</a>
            </div>
            @endforeach
        </div>

        <!-- Graphique Chart.js -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center gap-2">
                <i class="lucide lucide-bar-chart-2 text-indigo-500"></i>
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
        const stats = [{{ $coursCount ?? 0 }}, {{ $affluentCount ?? 0 }}, {{ $bassinCount ?? 0 }}, {{ $localiteCount ?? 0 }}, {{ $villeCount ?? 0 }}];

        stats.forEach((value, i) => {
            const el = document.getElementById(`count-${i}`);
            if (el) animateCount(el, value);
        });

        function animateCount(el, end) {
            let start = 0;
            const duration = 1000;
            const increment = end / (duration / 30);
            const interval = setInterval(() => {
                start += increment;
                if (start >= end) {
                    el.textContent = end;
                    clearInterval(interval);
                } else {
                    el.textContent = Math.floor(start);
                }
            }, 30);
        }

        const ctx = document.getElementById('dashboardChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.7)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Cours d\'eau', 'Affluents', 'Bassins versants', 'Localités', 'Villes'],
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
