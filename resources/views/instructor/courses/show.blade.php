@extends('layouts.app')
@section('title', $course->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div class="mb-8 flex flex-col md:flex-row md:items-start justify-between gap-4">
        <div>
            <a href="{{ route('instructor.courses.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-3 group">
                <svg class="w-4 h-4 mr-1.5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Volver a Mis Cursos
            </a>
            <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">
                {{ $course->title }}
            </h1>
            <span class="inline-block mt-3 px-3 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-xs font-black uppercase tracking-widest rounded-lg border border-red-100 dark:border-red-800/50">
                {{ $course->category->name ?? 'General' }}
            </span>
        </div>

        {{-- 🔥 BOTÓN NUEVO: Exportar Lista de Asistencia desde el Inicio 🔥 --}}
        <div class="shrink-0 mt-4 md:mt-0">
            <a href="{{ route('instructor.courses.export-students', $course) }}" target="_blank" class="w-full md:w-auto px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Lista de Asistencia PDF
            </a>
        </div>
    </div>

    {{-- 🔥 TARJETAS DE ESTADÍSTICAS 🔥 --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-10">
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row items-center sm:items-start gap-4 hover:-translate-y-1 transition-transform text-center sm:text-left group">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shadow-inner shrink-0 mx-auto sm:mx-0 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" /></svg>
            </div>
            <div>
                <p class="text-3xl font-black text-gray-900 dark:text-white leading-none mb-1">{{ $stats['students'] ?? 0 }}</p>
                <p class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">Estudiantes</p>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row items-center sm:items-start gap-4 hover:-translate-y-1 transition-transform text-center sm:text-left group">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-500/10 text-amber-500 flex items-center justify-center shadow-inner shrink-0 mx-auto sm:mx-0 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
            </div>
            <div>
                <p class="text-3xl font-black text-gray-900 dark:text-white leading-none mb-1">{{ $stats['modules'] ?? 0 }}</p>
                <p class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">Módulos</p>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row items-center sm:items-start gap-4 hover:-translate-y-1 transition-transform text-center sm:text-left group">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-500/10 text-purple-500 flex items-center justify-center shadow-inner shrink-0 mx-auto sm:mx-0 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
            </div>
            <div>
                <p class="text-3xl font-black text-gray-900 dark:text-white leading-none mb-1">{{ $stats['resources'] ?? 0 }}</p>
                <p class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">Recursos</p>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row items-center sm:items-start gap-4 hover:-translate-y-1 transition-transform text-center sm:text-left group">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 flex items-center justify-center shadow-inner shrink-0 mx-auto sm:mx-0 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
            <div>
                <p class="text-3xl font-black text-gray-900 dark:text-white leading-none mb-1">{{ $stats['completed'] ?? 0 }}</p>
                <p class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">Completaron</p>
            </div>
        </div>
    </div>

    {{-- 🔥 GRILLA DE ESTUDIANTES Y MÓDULOS 🔥 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 dark:border-slate-700/50 flex justify-between items-center bg-gray-50/50 dark:bg-[#0f172a]/50">
                <h2 class="text-lg font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                    Estudiantes Recientes
                </h2>
                <a href="{{ route('instructor.courses.students', $course) }}" class="text-xs font-bold text-blue-800 dark:text-blue-400 hover:text-blue-600 transition-colors bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-800/50 px-4 py-2 rounded-xl shadow-sm flex items-center gap-1 group">
                    Ver todos
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            
            <div class="p-0 flex-1">
                <ul class="divide-y divide-gray-100 dark:divide-slate-700/50">
                    @forelse($students as $student)
                        <li class="p-5 hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-800 text-white flex items-center justify-center font-bold text-sm shadow-sm border border-blue-900 shrink-0">
                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white text-sm line-clamp-1">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-slate-400 line-clamp-1">{{ $student->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 w-1/3 justify-end shrink-0">
                                <div class="hidden sm:block w-full h-2 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full" style="width: {{ $student->pivot->progress_percentage ?? 0 }}%"></div>
                                </div>
                                <span class="text-xs font-black text-gray-600 dark:text-slate-300">{{ $student->pivot->progress_percentage ?? 0 }}%</span>
                            </div>
                        </li>
                    @empty
                        <li class="p-10 text-center flex flex-col items-center justify-center text-gray-400 dark:text-slate-500">
                            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" /></svg>
                            <p class="text-sm font-medium">No hay estudiantes inscritos recientemente.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 dark:border-slate-700/50 flex justify-between items-center bg-gray-50/50 dark:bg-[#0f172a]/50">
                <h2 class="text-lg font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                    Módulos del Curso
                </h2>
                <a href="{{ route('instructor.courses.modules', $course) }}" class="text-xs font-bold text-red-600 dark:text-red-400 hover:text-red-700 transition-colors bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-800/50 px-4 py-2 rounded-xl shadow-sm flex items-center gap-1 group">
                    Gestionar
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
            
            <div class="p-0 flex-1">
                <ul class="divide-y divide-gray-100 dark:divide-slate-700/50">
                    @forelse($course->modules as $module)
                        <li class="p-5 hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors flex items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-600 flex items-center justify-center text-xs font-black text-gray-500 dark:text-slate-400 shrink-0 shadow-inner">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm line-clamp-1">{{ $module->title }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        {{ $module->resources_count ?? 0 }} recursos
                                    </p>
                                </div>
                            </div>
                            
                            <div class="shrink-0">
                                @if($module->is_visible)
                                    <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-blue-100 dark:border-blue-800/50">
                                        Publicado
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-amber-100 dark:border-amber-800/50">
                                        Borrador
                                    </span>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="p-10 text-center flex flex-col items-center justify-center text-gray-400 dark:text-slate-500">
                            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                            <p class="text-sm font-medium">Aún no has creado ningún módulo.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- 🔥 SECCIÓN GESTIÓN DE EVALUACIÓN (EXAMEN) 🔥 --}}
    <div class="bg-gradient-to-r from-blue-900 to-blue-950 dark:from-slate-800 dark:to-slate-900 rounded-3xl shadow-xl border border-blue-800 dark:border-slate-700 overflow-hidden relative">
        <div class="absolute inset-0 bg-white/5 opacity-20" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
        
        <div class="relative p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-6">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-white shadow-inner border border-white/20 shrink-0">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" /></svg>
                </div>
                <div>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mb-2">
                        <h2 class="text-2xl font-black text-white tracking-tight">Evaluación Final del Curso</h2>
                        @if($course->quiz)
                            @if($course->quiz->is_active)
                                <span class="px-3 py-1 bg-green-500/20 text-green-300 text-xs font-black uppercase tracking-widest rounded-lg border border-green-500/30 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> Activa y Visible
                                </span>
                            @else
                                <span class="px-3 py-1 bg-amber-500/20 text-amber-300 text-xs font-black uppercase tracking-widest rounded-lg border border-amber-500/30 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-400"></span> Oculta (Borrador)
                                </span>
                            @endif
                        @endif
                    </div>
                    <p class="text-blue-200 dark:text-slate-300 font-medium text-sm max-w-xl">
                        @if($course->quiz)
                            <strong>{{ $course->quiz->title }}</strong> • {{ $course->quiz->time_limit }} min • Mín. {{ $course->quiz->passing_score }}/20 pts.
                        @else
                            Configura las preguntas, opciones correctas y el estado del examen final para calificar a los estudiantes del INCES.
                        @endif
                    </p>
                </div>
            </div>

            <div class="shrink-0 w-full md:w-auto flex flex-col sm:flex-row items-center gap-3">
                @if($course->quiz)
                    <form action="{{ route('instructor.courses.quizzes.toggle', [$course, $course->quiz]) }}" method="POST" class="w-full sm:w-auto m-0">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-5 py-3.5 {{ $course->quiz->is_active ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-bold rounded-xl transition-all shadow-md flex items-center justify-center gap-2 text-sm">
                            @if($course->quiz->is_active)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                                Ocultar Examen
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                Activar Examen
                            @endif
                        </button>
                    </form>
                @endif

                <a href="{{ route('instructor.courses.quizzes.create', $course) }}" class="w-full sm:w-auto px-6 py-3.5 bg-red-600 hover:bg-red-700 text-white font-black rounded-xl shadow-[0_0_20px_rgba(220,38,38,0.4)] transition-all hover:-translate-y-1 flex items-center justify-center gap-2 text-sm group">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                    <span>{{ $course->quiz ? 'Reconfigurar Examen' : 'Crear Examen' }}</span>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
