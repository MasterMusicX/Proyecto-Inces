@extends('layouts.app')
@section('title', 'Estadísticas')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-display font-bold text-gray-900 dark:text-white">Estadísticas de la Plataforma</h1>
        <p class="text-gray-500 mt-1">Análisis de uso y rendimiento del INCES LMS</p>
    </div>

    <!-- AI Usage Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="card p-5 flex items-center gap-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-900/10 border-purple-200 dark:border-purple-800">
            <div class="w-14 h-14 bg-purple-500 rounded-2xl flex items-center justify-center text-white text-2xl flex-shrink-0">🤖</div>
            <div>
                <p class="text-3xl font-display font-bold text-purple-700 dark:text-purple-300">{{ number_format($aiStats['total_queries']) }}</p>
                <p class="text-sm text-purple-600 dark:text-purple-400">Consultas IA Totales</p>
            </div>
        </div>
        <div class="card p-5 flex items-center gap-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/10 border-blue-200 dark:border-blue-800">
            <div class="w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center text-white text-2xl flex-shrink-0">📅</div>
            <div>
                <p class="text-3xl font-display font-bold text-blue-700 dark:text-blue-300">{{ number_format($aiStats['today_queries']) }}</p>
                <p class="text-sm text-blue-600 dark:text-blue-400">Consultas Hoy</p>
            </div>
        </div>
        <div class="card p-5 flex items-center gap-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/10 border-green-200 dark:border-green-800">
            <div class="w-14 h-14 bg-green-500 rounded-2xl flex items-center justify-center text-white text-2xl flex-shrink-0">👍</div>
            <div>
                <p class="text-3xl font-display font-bold text-green-700 dark:text-green-300">{{ $aiStats['helpful_rate'] }}%</p>
                <p class="text-sm text-green-600 dark:text-green-400">Tasa de Satisfacción</p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Registrations Chart -->
        <div class="card p-6">
            <h2 class="font-display font-bold text-lg text-gray-900 dark:text-white mb-4">📈 Registros de Estudiantes (6 meses)</h2>
            <canvas id="registrationsChart" height="200"></canvas>
        </div>
        <!-- Enrollments Chart -->
        <div class="card p-6">
            <h2 class="font-display font-bold text-lg text-gray-900 dark:text-white mb-4">📚 Inscripciones en Cursos (6 meses)</h2>
            <canvas id="enrollmentsChart" height="200"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Resource Types Chart -->
        <div class="card p-6">
            <h2 class="font-display font-bold text-lg text-gray-900 dark:text-white mb-4">📊 Tipos de Recursos</h2>
            <canvas id="resourcesChart" height="220"></canvas>
        </div>
        <!-- User Roles Chart -->
        <div class="card p-6">
            <h2 class="font-display font-bold text-lg text-gray-900 dark:text-white mb-4">👥 Distribución de Usuarios</h2>
            <canvas id="usersChart" height="220"></canvas>
        </div>
        <!-- Top Courses -->
        <div class="card p-6">
            <h2 class="font-display font-bold text-lg text-gray-900 dark:text-white mb-4">🏆 Cursos Más Populares</h2>
            <div class="space-y-3">
                @foreach($topCourses->take(6) as $i => $course)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-700 dark:text-gray-300 truncate flex-1">{{ Str::limit($course->title, 28) }}</span>
                        <span class="font-bold text-brand-600 dark:text-brand-400 ml-2">{{ $course->enrollments_count }}</span>
                    </div>
                    <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                        @php $maxCount = $topCourses->first()->enrollments_count ?: 1; @endphp
                        <div class="h-full bg-gradient-to-r from-brand-400 to-brand-600 rounded-full"
                            style="width: {{ ($course->enrollments_count / $maxCount) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Enrollments -->
    <div class="card p-6">
        <h2 class="font-display font-bold text-lg text-gray-900 dark:text-white mb-4">🕐 Inscripciones Recientes</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase">Estudiante</th>
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase">Curso</th>
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase">Fecha</th>
                        <th class="text-left py-2 px-3 text-xs font-semibold text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentEnrollments as $enrollment)
                    <tr class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/20">
                        <td class="py-3 px-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $enrollment->user->avatar_url }}" class="w-7 h-7 rounded-full" alt="">
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $enrollment->user->name }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-3 text-gray-600 dark:text-gray-400">{{ Str::limit($enrollment->course->title, 35) }}</td>
                        <td class="py-3 px-3 text-gray-400 text-xs">{{ $enrollment->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-3 px-3">
                            <span class="badge {{ $enrollment->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $enrollment->status === 'completed' ? 'Completado' : 'Activo' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const isDark = document.documentElement.classList.contains('dark');
const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
const textColor = isDark ? '#94a3b8' : '#64748b';

// Registrations chart
const regData = @json($registrationsPerMonth);
new Chart(document.getElementById('registrationsChart'), {
    type: 'line',
    data: {
        labels: regData.map(d => d.month),
        datasets: [{
            label: 'Nuevos Estudiantes',
            data: regData.map(d => d.total),
            borderColor: '#005A9E',
            backgroundColor: 'rgba(0,90,158,0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#005A9E',
            pointRadius: 5,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1 } }, x: { grid: { color: gridColor }, ticks: { color: textColor } } } }
});

// Enrollments chart
const enrData = @json($enrollmentsPerMonth);
new Chart(document.getElementById('enrollmentsChart'), {
    type: 'bar',
    data: {
        labels: enrData.map(d => d.month),
        datasets: [{
            label: 'Inscripciones',
            data: enrData.map(d => d.total),
            backgroundColor: 'rgba(0,168,107,0.8)',
            borderColor: '#00A86B',
            borderWidth: 1,
            borderRadius: 8,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor } }, x: { grid: { display: false }, ticks: { color: textColor } } } }
});

// Resource types pie
const resData = @json($resourceTypes);
new Chart(document.getElementById('resourcesChart'), {
    type: 'doughnut',
    data: {
        labels: resData.map(d => d.type.toUpperCase()),
        datasets: [{ data: resData.map(d => d.total), backgroundColor: ['#005A9E','#00A86B','#F5A623','#D0021B','#7B68EE','#1ABC9C','#E74C3C'], borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { color: textColor, padding: 12, font: { size: 11 } } } } }
});

// User roles pie
const userData = @json($userRoles);
new Chart(document.getElementById('usersChart'), {
    type: 'doughnut',
    data: {
        labels: userData.map(d => { return {admin:'Admins',instructor:'Instructores',student:'Estudiantes'}[d.role] || d.role }),
        datasets: [{ data: userData.map(d => d.total), backgroundColor: ['#D0021B','#7B68EE','#005A9E'], borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { color: textColor, padding: 12, font: { size: 11 } } } } }
});
</script>
@endpush
