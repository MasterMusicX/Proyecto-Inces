@extends('layouts.app')
@section('title', 'Módulos - ' . $course->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div class="mb-8">
        <a href="{{ route('instructor.courses.show', $course) }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-3 group">
            <svg class="w-4 h-4 mr-1.5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Volver a Detalles del Curso
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Gestión de Módulos</h1>
        <p class="text-gray-500 dark:text-slate-400 mt-1">Curso: <span class="font-bold text-blue-800 dark:text-blue-400">{{ $course->title }}</span></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700/50 flex justify-between items-center bg-gray-50/50 dark:bg-[#0f172a]/50">
                    <h2 class="text-lg font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                        Módulos Actuales
                    </h2>
                </div>
                
                <div class="p-0">
                    <ul class="divide-y divide-gray-100 dark:divide-slate-700/50">
                        @forelse($modules as $module)
                        <li class="p-5 hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-800/50 text-blue-800 dark:text-blue-400 flex items-center justify-center font-black text-sm shrink-0 shadow-inner">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-gray-900 dark:text-white text-base">{{ $module->title }}</p>
                                    @if($module->description)
                                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 line-clamp-2">{{ $module->description }}</p>
                                    @endif
                                    
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="text-[11px] font-bold text-gray-400 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            {{ $module->resources_count ?? 0 }} recursos
                                        </span>
                                        @if($module->is_published)
                                            <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[9px] font-black uppercase tracking-widest rounded border border-blue-100 dark:border-blue-800/50 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                                Publicado
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase tracking-widest rounded border border-amber-100 dark:border-amber-800/50 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                Borrador
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <form method="POST" action="{{ route('instructor.courses.modules.destroy', [$course, $module]) }}" onsubmit="return confirm('¿Estás seguro de eliminar este módulo y todos sus recursos?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full sm:w-auto p-2 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-600 dark:hover:text-white rounded-xl transition-colors flex items-center justify-center gap-2" title="Eliminar Módulo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    <span class="sm:hidden text-xs font-bold uppercase tracking-wider">Eliminar</span>
                                </button>
                            </form>
                        </li>
                        @empty
                        <li class="p-12 text-center flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 mb-4 text-gray-400 dark:text-slate-500 opacity-50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                            <p class="text-gray-900 dark:text-white font-bold mb-1">Sin módulos creados</p>
                            <p class="text-gray-500 dark:text-slate-400 text-sm">Usa el formulario para agregar el primer módulo de tu curso.</p>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 sticky top-6">
                <h2 class="text-lg font-extrabold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Nuevo Módulo
                </h2>
                
                <form method="POST" action="{{ route('instructor.courses.modules.store', $course) }}" class="space-y-5">
                    @csrf
                    
                    @if($errors->any())
                        <div class="p-3 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-800/50 rounded-xl text-red-600 dark:text-red-400 text-xs font-bold flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Título *</label>
                        <input name="title" required placeholder="Ej: Unidad 1: Introducción"
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Descripción (Opcional)</label>
                        <textarea name="description" rows="3" placeholder="Breve resumen del contenido..."
                                  class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all resize-none"></textarea>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors" onclick="document.getElementById('pub').click()">
                        <input type="checkbox" name="is_published" value="1" checked id="pub" 
                               class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                        <label for="pub" class="text-sm font-bold text-gray-700 dark:text-slate-300 cursor-pointer select-none flex-1">
                            Publicar inmediatamente
                        </label>
                    </div>

                    <button type="submit" class="w-full py-3.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19.25V5.5A2.5 2.5 0 0 0 15 3H6a2.5 2.5 0 0 0-2.5 2.5v13.5A2.5 2.5 0 0 0 6 21h9.5a2.5 2.5 0 0 0 2.5-2.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25H9m6 3H9m3 3H9" /></svg>
                        Guardar Módulo
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
