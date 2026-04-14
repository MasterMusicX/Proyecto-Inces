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
        $cards = [
            ['🎓','Estudiantes',   $stats['total_users'] ?? 0,       'bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400'],
            ['👩‍🏫','Instructores',  $stats['total_instructors'] ?? 0, 'bg-purple-100 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400'],
            ['📚','Cursos',        $stats['total_courses'] ?? 0,     'bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400'],
            ['📄','Recursos',      $stats['total_resources'] ?? 0,   'bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400'],
            ['✅','Inscripciones', $stats['total_enrollments'] ?? 0, 'bg-cyan-100 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400'],
            ['🤖','Consultas IA',  $stats['total_ai_queries'] ?? 0,  'bg-pink-100 dark:bg-pink-500/20 text-pink-600 dark:text-pink-400'],
        ];
        ?>
        
        @foreach($cards as $i => [$icon,$label,$value,$colorClass])
        <div class="bg-white dark:bg-[#1e293b] rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col items-center text-center transition-all hover:-translate-y-1 hover:shadow-md animate-fade-in-up" style="animation-delay: {{ $i * 50 }}ms;">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl mb-3 {{ $colorClass }}">
                {{ $icon }}
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
                    <span>👥</span> Usuarios Recientes
                </h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                    Ver todos &rarr;
                </a>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                @forelse($recentUsers ?? [] as $u)
                <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-50 dark:border-slate-700/50 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors group cursor-pointer">
                    <img src="{{ $u->avatar_url ?? 'https://ui-avatars.com/api/?name='.$u->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-white dark:border-slate-700 shadow-sm">
                    
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
                    <span>🏆</span> Cursos Populares
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
                        <div class="text-xs font-medium text-gray-500 dark:text-slate-400 flex items-center gap-1">
                            <span>👨‍🏫</span> {{ $c->instructor->name ?? 'Sin Instructor' }}
                        </div>
                    </div>
                    
                    <div class="text-sm font-black text-gray-900 dark:text-white bg-gray-100 dark:bg-slate-700 px-3 py-1 rounded-lg">
                        {{ $c->enrollments_count ?? 0 }} <span class="text-blue-500">👥</span>
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
            <span>⚡</span> Acciones Rápidas
        </h2>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-bold rounded-xl bg-blue-500 hover:bg-blue-600 text-white shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-0.5">
                ➕ Nuevo Usuario
            </a>
            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-bold rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-200 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                📚 Nuevo Curso
            </a>
            <a href="{{ route('admin.statistics') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-bold rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-200 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                📈 Ver Estadísticas
            </a>
            <a href="{{ route('admin.knowledge-base.index') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-bold rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-200 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                🧠 Base de Conocimiento
            </a>
        </div>
    </div>

</div>
@endsection
