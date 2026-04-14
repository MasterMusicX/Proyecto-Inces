@extends('layouts.app')
@section('title', 'Módulos - ' . $course->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div class="mb-8">
        <a href="{{ route('instructor.courses.show', $course) }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-3">
            ← Volver a Detalles del Curso
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Gestión de Módulos</h1>
        <p class="text-gray-500 dark:text-slate-400 mt-1">Curso: <span class="font-bold text-blue-800 dark:text-blue-400">{{ $course->title }}</span></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700/50 flex justify-between items-center bg-gray-50/50 dark:bg-[#0f172a]/50">
                    <h2 class="text-lg font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="text-xl">📚</span> Módulos Actuales
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
                                            <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[9px] font-black uppercase tracking-widest rounded border border-blue-100 dark:border-blue-800/50">
                                                Publicado
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase tracking-widest rounded border border-amber-100 dark:border-amber-800/50">
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
                            <span class="text-5xl mb-4 opacity-50">📑</span>
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
                    <span class="text-xl text-blue-500">➕</span> Nuevo Módulo
                </h2>
                
                <form method="POST" action="{{ route('instructor.courses.modules.store', $course) }}" class="space-y-5">
                    @csrf
                    
                    @if($errors->any())
                        <div class="p-3 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-800/50 rounded-xl text-red-600 dark:text-red-400 text-xs font-bold">
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
                        💾 Guardar Módulo
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
