@extends('layouts.app')
@section('title', $course->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <a href="{{ route('student.courses.show', $course) }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-4">
        ← Volver a Detalles del Curso
    </a>

    <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0 shadow-inner">📚</div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $course->title }}</h1>
                <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1">Instructor: <span class="font-bold text-gray-700 dark:text-slate-300">{{ $course->instructor->name }}</span></p>
            </div>
        </div>
        
        <a href="{{ route('student.chatbot') }}" class="shrink-0 px-5 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800/50 rounded-xl text-sm font-bold flex items-center justify-center gap-2 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-colors shadow-sm w-full sm:w-auto">
            🤖 Asistente IA
        </a>
    </div>

    <div class="space-y-5">
        @forelse($course->modules as $module)
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden" x-data="{ open: true }">
            
            <button @click="open = !open" class="w-full flex items-center justify-between p-6 hover:bg-gray-50 dark:hover:bg-[#0f172a] transition-colors focus:outline-none">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center font-black text-sm shadow-inner border border-blue-100 dark:border-blue-800/50 shrink-0">
                        {{ $loop->iteration }}
                    </div>
                    <div class="text-left">
                        <h3 class="font-extrabold text-gray-900 dark:text-white text-base">{{ $module->title }}</h3>
                        <span class="text-xs font-bold text-gray-500 dark:text-slate-400 mt-1 block">{{ $module->resources->count() }} recursos</span>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div x-show="open" x-collapse x-cloak class="border-t border-gray-100 dark:border-slate-700/50 bg-gray-50/50 dark:bg-[#0f172a]/30">
                <div class="p-2">
                    @forelse($module->resources->where('is_published', true) as $resource)
                        <a href="{{ route('student.resources.show', $resource) }}" class="flex items-center gap-4 p-4 hover:bg-white dark:hover:bg-[#1e293b] rounded-2xl transition-all border border-transparent hover:border-gray-200 dark:hover:border-slate-600 hover:shadow-sm group my-1">
                            
                            <span class="text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">{{ $resource->type_icon }}</span>
                            
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $resource->title }}</p>
                                @if($resource->description)
                                    <p class="text-xs text-gray-500 dark:text-slate-400 truncate mt-0.5">{{ $resource->description }}</p>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 flex-shrink-0">
                                <div class="text-right hidden sm:block">
                                    <span class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest block">{{ $resource->type }}</span>
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
                            <p class="text-sm font-bold text-gray-400 dark:text-slate-500 italic">Este módulo aún no tiene recursos publicados.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-12 text-center shadow-sm border border-gray-100 dark:border-slate-700/50">
            <div class="text-6xl mb-4 opacity-50">📭</div>
            <h3 class="text-lg font-extrabold text-gray-900 dark:text-white">Sin Módulos</h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-2">El instructor aún no ha cargado contenido en este curso. ¡Vuelve pronto!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection