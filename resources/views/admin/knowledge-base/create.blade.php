@extends('layouts.app')
@section('title', 'Nueva Entrada | Base de Conocimiento')

@section('content')
<div class="max-w-4xl mx-auto pb-10">
    
    <div class="flex items-center justify-between mb-8 animate-fade-in-up">
        <div>
            <a href="{{ route('admin.knowledge-base.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 transition-colors mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a la Base
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Nueva Entrada de Conocimiento</h1>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden animate-fade-in-up" style="animation-delay: 100ms;">
        
        <form action="{{ route('admin.knowledge-base.store') }}" method="POST" class="p-6 md:p-10 space-y-8">
            @csrf

            <div class="space-y-6">
                <h2 class="text-lg font-black text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700/50 pb-2 uppercase tracking-wider">
                    Contenido Principal
                </h2>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Pregunta <span class="text-rose-500">*</span></label>
                    <input type="text" name="question" required value="{{ old('question') }}" placeholder="Ej: ¿Cómo me inscribo en un curso?" 
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3.5 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-2">Escribe la pregunta tal cual la haría un usuario.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Respuesta <span class="text-rose-500">*</span></label>
                    <textarea name="answer" required rows="10" placeholder="Escribe la respuesta detallada aquí..." 
                              class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3.5 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-allresize-none">{{ old('answer') }}</textarea>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-2">Esta será la información que la IA usará para responder.</p>
                </div>
            </div>

            <div class="space-y-6 pt-4">
                <h2 class="text-lg font-black text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700/50 pb-2 uppercase tracking-wider">
                    Clasificación y Metadatos
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Categoría <span class="text-rose-500">*</span></label>
                        <input type="text" name="category" required value="{{ old('category', 'faq') }}" placeholder="Ej: inscripciones, plataforma, certificados" 
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-2">Usa minúsculas y sin espacios para agrupar.</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Palabras Clave (Tags) - Separa con comas</label>
                        <input type="text" name="tags_string" value="{{ old('tags_string') }}" placeholder="Ej: registro, curso, ayuda" 
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-2">Ayuda a la IA a indexar mejor la información.</p>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex items-center justify-end gap-4 border-t border-gray-100 dark:border-slate-700/50">
                <a href="{{ route('admin.knowledge-base.index') }}" class="px-6 py-3.5 text-sm font-bold text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-8 py-3.5 text-sm font-bold text-white bg-blue-500 hover:bg-blue-600 rounded-xl shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Guardar Entrada
                </button>
            </div>

        </form>
    </div>
</div>
@endsection