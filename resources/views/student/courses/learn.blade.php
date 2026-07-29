@extends('layouts.app')
@section('title', $course->title . ' | Aula Virtual')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    {{-- Botón de retroceso --}}
    <a href="{{ route('student.courses.show', $course->slug ?? $course->id) }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-4 group">
        <span class="transform transition-transform group-hover:-translate-x-1 mr-1">&larr;</span> Volver a Detalles del Curso
    </a>

    {{-- =========================================================================
         CABECERA DEL AULA VIRTUAL
         ========================================================================= --}}
    <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-900 to-blue-700 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0 shadow-inner">📚</div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $course->title }}</h1>
                <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1">Maestro Técnico Productivo: <span class="font-bold text-gray-700 dark:text-slate-300">{{ $course->instructor->name ?? 'INCES' }}</span></p>
            </div>
        </div>
        
        {{-- Botón del Asistente Virtual (Chatbot de Gemini) --}}
        <a href="{{ route('student.chatbot') }}" class="shrink-0 px-5 py-3 bg-blue-50 dark:bg-blue-500/10 text-blue-800 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50 rounded-xl text-sm font-black flex items-center justify-center gap-2 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-all shadow-sm w-full sm:w-auto hover:-translate-y-0.5">
            <span class="text-lg">🤖</span> Asistente IA
        </a>
    </div>

    {{-- =========================================================================
         LISTADO DE MÓDULOS Y RECURSOS
         ========================================================================= --}}
    <div class="space-y-5">
        @forelse($course->modules as $module)
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden transition-all" x-data="{ open: true }">
            
            {{-- Acordeón - Título del Módulo --}}
            <button @click="open = !open" class="w-full flex items-center justify-between p-6 hover:bg-gray-50 dark:hover:bg-[#0f172a] transition-colors focus:outline-none">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center font-black text-sm shadow-inner border border-blue-200 dark:border-blue-800/50 shrink-0">
                        {{ $loop->iteration }}
                    </div>
                    <div class="text-left">
                        <h3 class="font-extrabold text-gray-900 dark:text-white text-base">{{ $module->title }}</h3>
                        <span class="text-xs font-bold text-gray-500 dark:text-slate-400 mt-1 block">{{ $module->resources->count() }} recursos de aprendizaje</span>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            {{-- Contenido del Módulo --}}
            <div x-show="open" x-collapse x-cloak class="border-t border-gray-100 dark:border-slate-700/50 bg-gray-50/50 dark:bg-[#0f172a]/30">
                <div class="p-2">
                    
                    {{-- Ciclo de Recursos (PDFs, Videos, etc) --}}
                    @php $publishedResources = $module->resources->filter(fn($r) => $r->is_published); @endphp
                    @forelse($publishedResources as $resource)
                        <div class="flex items-center gap-4 p-4 hover:bg-white dark:hover:bg-[#1e293b] rounded-2xl transition-all border border-transparent hover:border-gray-200 dark:hover:border-slate-600 hover:shadow-sm group my-1">
                            
                            <a href="{{ route('student.resources.show', $resource) }}" class="flex items-center gap-4 flex-1 min-w-0">
                                <span class="p-2 rounded-lg bg-gray-100 dark:bg-slate-800 flex-shrink-0 group-hover:scale-110 transition-transform">{!! $resource->type_icon !!}</span>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-sm text-gray-900 dark:text-white truncate group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $resource->title }}</p>
                                    @if($resource->description)
                                        <p class="text-xs text-gray-500 dark:text-slate-400 truncate mt-0.5">{{ $resource->description }}</p>
                                    @endif
                                </div>
                            </a>

                            <div class="flex items-center gap-3 flex-shrink-0">
                                <div class="text-right hidden sm:block">
                                    <span class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest block">{{ $resource->type ?? 'Documento' }}</span>
                                    @if($resource->file_size)
                                        <span class="text-[10px] font-bold text-gray-400">{{ $resource->file_size_human }}</span>
                                    @endif
                                </div>

                                <a href="{{ route('student.resources.show', $resource) }}" title="Ver Recurso" class="px-3 py-1.5 text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                    <span class="hidden md:inline">Ver</span>
                                </a>

                                @if($resource->is_downloadable)
                                    <a href="{{ route('student.resources.download', $resource) }}" title="Descargar Recurso" class="px-3 py-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-500/10 dark:hover:bg-blue-500/20 rounded-lg transition-colors flex items-center gap-1">
                                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                        <span class="hidden md:inline">Descargar</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <p class="text-sm font-bold text-gray-400 dark:text-slate-500 italic">Este módulo aún no tiene recursos publicados por el instructor.</p>
                        </div>
                    @endforelse

                    {{-- 🔥 MEJORA: BOTÓN DE PROGRESO EN TIEMPO REAL 🔥 --}}
                    @if($publishedResources->count() > 0)
                        <div class="mt-4 mb-2 mx-2">
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 p-5 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div>
                                    <h4 class="text-base font-extrabold text-blue-900 dark:text-white flex items-center gap-2">
                                        🎓 ¿Terminaste de estudiar este módulo?
                                    </h4>
                                    <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">Asegúrate de haber revisado todo el material y luego presiona el botón para actualizar tu progreso oficial en el sistema.</p>
                                </div>
                                
                                <form action="{{ route('student.courses.progress', $course->slug ?? $course->id) }}" method="POST" class="w-full sm:w-auto m-0 shrink-0">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Completar Módulo
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-12 text-center shadow-sm border border-gray-100 dark:border-slate-700/50">
            <div class="text-6xl mb-4 opacity-50">📭</div>
            <h3 class="text-lg font-extrabold text-gray-900 dark:text-white">Formación sin contenido</h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-2">El MTP aún no ha cargado los módulos correspondientes a esta formación. ¡Vuelve pronto!</p>
        </div>
        @endforelse
    </div>

    {{-- 🔥 SECCIÓN DE EVALUACIÓN FINAL DEL CURSO 🔥 --}}
    @if($course->quiz)
    <div class="mt-8 bg-gradient-to-r from-blue-900 to-indigo-950 dark:from-slate-800 dark:to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-blue-800 dark:border-slate-700 relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-white shrink-0 border border-white/20 shadow-inner">
                    <svg class="w-8 h-8 text-blue-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12" /></svg>
                </div>
                <div>
                    <span class="px-3 py-1 bg-blue-500/20 text-blue-300 text-[10px] font-black uppercase tracking-widest rounded-md border border-blue-400/30 mb-2 inline-block">Evaluación Obligatoria</span>
                    <h3 class="text-xl font-black text-white tracking-tight">{{ $course->quiz->title }}</h3>
                    <p class="text-xs text-blue-200 dark:text-slate-300 mt-1">
                        ⏱️ Tiempo: <strong>{{ $course->quiz->time_limit }} min</strong> &nbsp;•&nbsp; 🎯 Nota mín: <strong>{{ $course->quiz->passing_score }}/20 pts</strong>
                    </p>
                </div>
            </div>

            <div class="shrink-0 w-full md:w-auto">
                @if($course->quiz->is_active)
                    <a href="{{ route('student.quizzes.show', [$course->slug ?? $course->id, $course->quiz->id]) }}" class="w-full md:w-auto px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-black rounded-xl shadow-[0_0_20px_rgba(220,38,38,0.4)] transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm group">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        <span>Presentar Evaluación Final</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                @else
                    <span class="px-6 py-3.5 bg-gray-700/50 text-gray-300 font-bold rounded-xl text-xs flex items-center gap-2 border border-gray-600/50">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                        Evaluación Temporalmente Cerrada
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection