@extends('layouts.app')
@section('title', 'Panel Administrativo')

@section('content')
<div class="flex flex-col gap-6 max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="animate-fade-in-up">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-1">
            Panel de Administración
        </h1>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400">
            Gestión completa de la plataforma INCES LMS
        </p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <?php
        // Definimos los iconos SVG como strings para inyectarlos en el HTML
        $iconStudents = '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" /></svg>';
        $iconInstructors = '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>';
        $iconCourses = '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>';
        $iconResources = '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>';
        $iconEnrollments = '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" /></svg>';
        $iconAI = '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09l2.846.813-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" /></svg>';

        $cards = [
            [$iconStudents,    'Estudiantes',   $stats['total_users'] ?? 0,       'bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400'],
            [$iconInstructors, 'Instructores',  $stats['total_instructors'] ?? 0, 'bg-purple-100 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400'],
            [$iconCourses,     'Cursos',        $stats['total_courses'] ?? 0,     'bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400'],
            [$iconResources,   'Recursos',      $stats['total_resources'] ?? 0,   'bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400'],
            [$iconEnrollments, 'Inscripciones', $stats['total_enrollments'] ?? 0, 'bg-cyan-100 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400'],
            [$iconAI,          'Consultas IA',  $stats['total_ai_queries'] ?? 0,  'bg-pink-100 dark:bg-pink-500/20 text-pink-600 dark:text-pink-400'],
        ];
        ?>
        
        @foreach($cards as $i => [$icon,$label,$value,$colorClass])
        <div class="bg-white dark:bg-[#1e293b] rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col items-center text-center transition-all hover:-translate-y-1 hover:shadow-md animate-fade-in-up" style="animation-delay: {{ $i * 50 }}ms;">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 {{ $colorClass }}">
                {!! $icon !!} {{-- Usamos {!! !!} para renderizar el HTML del SVG --}}
            </div>
            <div class="text-2xl font-black text-gray-900 dark:text-white leading-none mb-1">
                {{ number_format($value) }}
            </div>
            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400">
                {{ $label }}
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tables row (Usuarios y Cursos) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent Users --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col overflow-hidden animate-fade-in-up" style="animation-delay: 200ms;">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700/50 flex items-center justify-between bg-gray-50/50 dark:bg-[#0f172a]/50">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    Usuarios Recientes
                </h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                    Ver todos &rarr;
                </a>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                @forelse($recentUsers ?? [] as $u)
                <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-50 dark:border-slate-700/50 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors group cursor-pointer">
                    
                    @php
                        $avatar = $u->avatar;
                        $avatarUrl = $avatar 
                            ? (str_starts_with($avatar, 'http') ? $avatar : asset('storage/' . $avatar))
                            : 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=ce202a&color=fff&bold=true';
                    @endphp
                    
                    <img src="{{ $avatarUrl }}" 
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=ce202a&color=fff&bold=true';"
                         alt="Avatar de {{ $u->name }}"
                         class="w-10 h-10 rounded-full object-cover border-2 border-white dark:border-slate-700 shadow-sm">
                    
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $u->name }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 truncate">
                            {{ $u->email }}
                        </div>
                    </div>
                    
                    <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full 
                        {{ $u->role === 'admin' ? 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400' : 
                          ($u->role === 'instructor' ? 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400' : 
                          'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400') }}">
                        {{ $u->role }}
                    </span>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500 dark:text-slate-400 text-sm">No hay usuarios recientes.</div>
                @endforelse
            </div>
        </div>

        {{-- Popular Courses --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col overflow-hidden animate-fade-in-up" style="animation-delay: 300ms;">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700/50 flex items-center justify-between bg-gray-50/50 dark:bg-[#0f172a]/50">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                    </svg>
                    Cursos Populares
                </h2>
                <a href="{{ route('admin.courses.index') }}" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                    Ver todos &rarr;
                </a>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                @forelse($popularCourses ?? [] as $i => $c)
                <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-50 dark:border-slate-700/50 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors group cursor-pointer">
                    
                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center font-black text-sm flex-shrink-0 group-hover:scale-110 transition-transform">
                        {{ $i + 1 }}
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $c->title }}
                        </div>
                        <div class="text-xs font-medium text-gray-500 dark:text-slate-400 flex items-center gap-1.5 mt-0.5">
                            <svg class="w-3.5 h-3.5 shrink-0 opacity-70" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                            {{ $c->instructor->name ?? 'Sin Instructor' }}
                        </div>
                    </div>
                    
                    <div class="text-sm font-black text-gray-900 dark:text-white bg-gray-100 dark:bg-slate-700 px-3 py-1 rounded-lg flex items-center gap-1.5">
                        {{ $c->enrollments_count ?? 0 }} 
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500 dark:text-slate-400 text-sm">No hay cursos populares aún.</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Quick Actions --}}
    <div class="bg-white dark:bg-[#1e293b] rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 animate-fade-in-up" style="animation-delay: 400ms;">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
            </svg>
            Acciones Rápidas
        </h2>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl bg-blue-500 hover:bg-blue-600 text-white shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-0.5">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" /></svg>
                Nuevo Usuario
            </a>
            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-200 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m4.06-7.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" /></svg>
                Nuevo Curso
            </a>
            <a href="{{ route('admin.statistics') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-200 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                Ver Estadísticas
            </a>
            <a href="{{ route('admin.knowledge-base.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-200 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.829 1.508-2.316a7.5 7.5 0 1 0-7.536 0c.85.487 1.508 1.333 1.508 2.316V18" /></svg>
                Base de Conocimiento
            </a>
        </div>
    </div>

</div>
@endsection
