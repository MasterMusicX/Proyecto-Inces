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
                    @forelse($module->resources->where('is_published', true) as $resource)
                        <a href="{{ route('student.resources.show', $resource) }}" class="flex items-center gap-4 p-4 hover:bg-white dark:hover:bg-[#1e293b] rounded-2xl transition-all border border-transparent hover:border-gray-200 dark:hover:border-slate-600 hover:shadow-sm group my-1">
                            
                            <span class="text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">{{ $resource->type_icon ?? '📄' }}</span>
                            
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-gray-900 dark:text-white truncate group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $resource->title }}</p>
                                @if($resource->description)
                                    <p class="text-xs text-gray-500 dark:text-slate-400 truncate mt-0.5">{{ $resource->description }}</p>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 flex-shrink-0">
                                <div class="text-right hidden sm:block">
                                    <span class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest block">{{ $resource->type ?? 'Documento' }}</span>
                                    @if($resource->file_size)
                                        <span class="text-[10px] font-bold text-gray-400">{{ $resource->file_size_human }}</span>
                                    @endif
                                </div>
                                <span class="w-8 h-8 rounded-full bg-gray-100 dark:bg-slate-800 text-gray-400 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-600 dark:group-hover:text-blue-400 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <p class="text-sm font-bold text-gray-400 dark:text-slate-500 italic">Este módulo aún no tiene recursos publicados por el instructor.</p>
                        </div>
                    @endforelse

                    {{-- 🔥 MEJORA: BOTÓN DE PROGRESO EN TIEMPO REAL 🔥 --}}
                    @if($module->resources->where('is_published', true)->count() > 0)
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
</div>
@endsection