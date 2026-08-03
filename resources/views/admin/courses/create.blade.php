@extends('layouts.app')
@section('title', 'Crear Nuevo Curso')

@section('content')
<div class="max-w-5xl mx-auto pb-10 animate-fade-in-up">
    
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 transition-colors mb-2">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"></path></svg>
                Volver a Cursos
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Crear Nuevo Curso</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-rose-50 dark:bg-rose-900/20 border-l-4 border-rose-500 rounded-r-xl p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-rose-800 dark:text-rose-400">Por favor, corrige los siguientes errores:</h3>
                    <ul class="mt-2 text-xs text-rose-700 dark:text-rose-300 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden">
        
        <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-10 space-y-8">
            @csrf

            <div class="space-y-6">
                <h2 class="text-lg font-black text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700/50 pb-2 uppercase tracking-wider">
                    Información General
                </h2>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Título del Curso <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="Ej: Introducción a la Electricidad Básica" 
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Categoría</label>
                        <select name="category_id" class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer">
                            <option value="">Seleccionar Categoría...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Instructor <span class="text-rose-500">*</span></label>
                        <select name="instructor_id" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer">
                            <option value="">Seleccionar Instructor...</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Descripción del Curso <span class="text-rose-500">*</span></label>
                    <textarea name="description" required rows="4" placeholder="Explica de qué trata este curso..." 
                              class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all resize-none">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Objetivos (Opcional)</label>
                    <textarea name="objectives" rows="3" placeholder="Al finalizar el curso, el participante será capaz de..." 
                              class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all resize-none">{{ old('objectives') }}</textarea>
                </div>
            </div>

            <div class="space-y-6 pt-4">
                <h2 class="text-lg font-black text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700/50 pb-2 uppercase tracking-wider">
                    Detalles de Configuración
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Nivel *</label>
                        <select name="level" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer">
                            <option value="basico" {{ old('level', 'basico') == 'basico' ? 'selected' : '' }}>Básico</option>
                            <option value="intermedio" {{ old('level') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                            <option value="avanzado" {{ old('level') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Estado *</label>
                        <select name="status" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Borrador</option>
                            <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Publicado</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archivado</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Duración (Horas)</label>
                        <input type="number" name="duration_hours" min="0" value="{{ old('duration_hours', 0) }}" 
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Max. Estudiantes</label>
                        <input type="number" name="max_students" min="1" value="{{ old('max_students') }}" placeholder="Vacío = Ilimitado" 
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class="space-y-6 pt-4">
                <h2 class="text-lg font-black text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700/50 pb-2 uppercase tracking-wider">
                    Multimedia y Destacados
                </h2>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Imagen de Portada</label>
                    <input type="file" name="thumbnail" accept="image/*" 
                           class="w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-500/20 dark:file:text-blue-400 dark:hover:file:bg-blue-500/30 transition-all cursor-pointer border border-gray-200 dark:border-slate-700 rounded-xl bg-gray-50 dark:bg-[#0f172a]">
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-2">Formatos recomendados: JPG, PNG. Tamaño ideal: 1280x720px.</p>
                </div>

                <div class="pt-4">
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" class="sr-only peer" {{ old('is_featured') ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        <span class="ml-4 text-sm font-bold text-gray-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Marcar como Curso Destacado</span>
                    </label>
                </div>
            </div>

            <div class="pt-8 flex items-center justify-end gap-4 border-t border-gray-100 dark:border-slate-700/50">
                <a href="{{ route('admin.courses.index') }}" class="px-6 py-3.5 text-sm font-bold text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-8 py-3.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-0.5 gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    <span>Guardar y Crear Curso</span>
                </button>
            </div>

        </form>
    </div>
</div>
@endsection