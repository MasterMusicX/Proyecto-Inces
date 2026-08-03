@extends('layouts.app')
@section('title', 'Editar Categoría: ' . $category->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div class="mb-8">
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-3 group">
            <svg class="w-4 h-4 mr-1.5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"></path></svg>
            Volver a Categorías
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
            Editar Categoría
        </h1>
        <p class="text-gray-500 dark:text-slate-400 mt-2">
            Actualiza el nombre, descripción y apariencia de la categoría <span class="font-bold text-blue-800 dark:text-blue-400">{{ $category->name }}</span>.
        </p>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden">
        
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT') 
            
            @if($errors->any())
                <div class="p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-800/50 rounded-xl mb-6">
                    <ul class="list-disc list-inside text-xs font-bold text-red-600 dark:text-red-400">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nombre de la Categoría *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required 
                       placeholder="Ej: Automatización Industrial"
                       class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Descripción</label>
                <textarea name="description" rows="4" placeholder="¿De qué trata esta área de formación?"
                          class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all resize-none">{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Color Distintivo</label>
                <input type="color" name="color" value="{{ old('color', $category->color ?? '#3B82F6') }}" 
                       class="h-12 w-full rounded-xl cursor-pointer bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 p-1">
            </div>

            <div class="pt-8 border-t border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" 
                   class="w-full sm:w-auto text-center px-8 py-3.5 text-sm font-bold text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                    Cancelar
                </a>
                
                <button type="submit" 
                        class="w-full sm:w-auto px-8 py-3.5 text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 rounded-xl shadow-lg shadow-blue-700/30 transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19.25V5.5A2.5 2.5 0 0 0 15 3H6a2.5 2.5 0 0 0-2.5 2.5v13.5A2.5 2.5 0 0 0 6 21h9.5a2.5 2.5 0 0 0 2.5-2.5Z" /></svg>
                    Guardar Cambios
                </button>
            </div>
            
        </form>
    </div>

    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-800/50 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
        <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed">
            Al cambiar el nombre de la categoría, el sistema actualizará automáticamente el <strong>slug</strong> amigable para las URLs. Los cursos asociados a esta área no se verán afectados.
        </p>
    </div>
</div>
@endsection