@extends('layouts.app')
@section('title', 'Editar Curso: ' . $course->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div class="mb-8">
        <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-700 dark:hover:text-blue-400 transition-colors mb-3">
            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            Volver a Gestión de Cursos
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
            Editar: <span class="text-blue-800 dark:text-blue-400">{{ $course->title }}</span>
        </h1>
        <p class="text-gray-500 dark:text-slate-400 mt-2">Modifica la información, estado, imagen y configuración de este curso.</p>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden">
        
        <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            @csrf 
            @method('PUT')

            @if($errors->any())
                <div class="p-4 bg-rose-50 dark:bg-rose-500/10 border-l-4 border-rose-500 rounded-r-xl mb-6">
                    <ul class="list-disc list-inside text-xs font-bold text-rose-600 dark:text-rose-400 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Título del Curso *</label>
                <input type="text" name="title" value="{{ old('title', $course->title) }}" required
                       class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Descripción *</label>
                <textarea name="description" rows="4" required
                          class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all resize-none">{{ old('description', $course->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Objetivos de Aprendizaje</label>
                <textarea name="objectives" rows="3" placeholder="¿Qué aprenderá el estudiante?"
                          class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all resize-none">{{ old('objectives', $course->objectives) }}</textarea>
            </div>

            <div class="w-full border-t border-gray-100 dark:border-slate-700/50 my-6"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Instructor *</label>
                    <select name="instructor_id" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all cursor-pointer">
                        @foreach($instructors as $i)
                            <option value="{{ $i->id }}" {{ old('instructor_id', $course->instructor_id) == $i->id ? 'selected' : '' }}>{{ $i->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Categoría</label>
                    <select name="category_id" class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all cursor-pointer">
                        <option value="">Sin categoría</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id', $course->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nivel *</label>
                    @php
                        $currLevel = old('level', $course->level);
                    @endphp
                    <select name="level" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all cursor-pointer">
                        <option value="basico" {{ in_array($currLevel, ['basico', 'beginner']) ? 'selected' : '' }}>Básico</option>
                        <option value="intermedio" {{ in_array($currLevel, ['intermedio', 'intermediate']) ? 'selected' : '' }}>Intermedio</option>
                        <option value="avanzado" {{ in_array($currLevel, ['avanzado', 'advanced']) ? 'selected' : '' }}>Avanzado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Estado del Curso *</label>
                    @php
                        $currStatus = old('status', $course->status);
                    @endphp
                    <select name="status" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all cursor-pointer">
                        <option value="draft" {{ $currStatus === 'draft' ? 'selected' : '' }}>Borrador</option>
                        <option value="published" {{ $currStatus === 'published' ? 'selected' : '' }}>Publicado</option>
                        <option value="archived" {{ $currStatus === 'archived' ? 'selected' : '' }}>Archivado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Duración (Horas)</label>
                    <input type="number" name="duration_hours" value="{{ old('duration_hours', $course->duration_hours) }}" min="0"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Máximo Estudiantes</label>
                    <input type="number" name="max_students" value="{{ old('max_students', $course->max_students) }}" min="1" placeholder="Ej: 50"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all">
                </div>
            </div>

            <div class="w-full border-t border-gray-100 dark:border-slate-700/50 my-6"></div>

            <div class="flex flex-col md:flex-row gap-6">
                @if($course->thumbnail)
                    <div class="shrink-0">
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Imagen Actual</label>
                        <div class="w-40 h-28 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-slate-700">
                            <img src="{{ $course->thumbnail_url }}" class="w-full h-full object-cover" alt="{{ $course->title }}">
                        </div>
                    </div>
                @endif
                
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nueva Imagen de Portada (Opcional)</label>
                    <input type="file" name="thumbnail" accept="image/*"
                           class="block w-full text-sm text-gray-500 dark:text-slate-400
                                  file:mr-4 file:py-3 file:px-4
                                  file:rounded-xl file:border-0
                                  file:text-sm file:font-bold
                                  file:bg-blue-50 file:text-blue-800
                                  hover:file:bg-blue-100 transition-all
                                  dark:file:bg-blue-900/30 dark:file:text-blue-400 dark:hover:file:bg-blue-900/50 cursor-pointer">
                    <p class="text-[10px] text-gray-400 mt-2">Formatos aceptados: JPG, PNG. Tamaño máximo recomendado: 2MB.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors mt-6" onclick="document.getElementById('featured').click()">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $course->is_featured) ? 'checked' : '' }} id="featured" 
                       class="w-5 h-5 text-blue-800 bg-white border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                <div class="flex-1 select-none">
                    <label for="featured" class="text-sm font-bold text-gray-900 dark:text-white cursor-pointer block">
                        Marcar como Curso Destacado
                    </label>
                    <p class="text-xs text-gray-500 dark:text-slate-400">El curso aparecerá en la sección principal del panel de los estudiantes.</p>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('admin.courses.index') }}" class="w-full sm:w-auto text-center px-6 py-3 text-sm font-bold text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                    Cancelar
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    <span>Actualizar Curso</span>
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection