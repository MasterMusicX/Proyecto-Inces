@extends('layouts.app')
@section('title', 'Editar Entrada KB')
@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
            <a href="{{ route('admin.knowledge-base.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a la Base de Conocimientos
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Editar Entrada</h1>
        <p class="text-gray-500 text-sm mt-1">Modifica los detalles y la información de esta pregunta frecuente.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form method="POST" action="{{ route('admin.knowledge-base.update', $knowledgeBase) }}" class="p-6 sm:p-8 space-y-8">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Categoría <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition-colors">
                        @foreach(['faq' => 'FAQ General', 'cursos' => 'Cursos', 'plataforma' => 'Plataforma', 'certificados' => 'Certificados', 'inces' => 'Sobre el INCES', 'tecnico' => 'Soporte Técnico'] as $v => $l)
                            <option value="{{ $v }}" {{ $knowledgeBase->category === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 dark:border-gray-600 rounded-lg w-full hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <input type="checkbox" name="is_active" value="1" {{ $knowledgeBase->is_active ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mantener entrada visible (Activa)</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pregunta <span class="text-red-500">*</span></label>
                <input type="text" name="question" value="{{ old('question', $knowledgeBase->question) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition-colors" placeholder="Ej: ¿Cómo me inscribo en un curso?">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Respuesta <span class="text-red-500">*</span></label>
                <textarea name="answer" rows="5" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition-colors" placeholder="Escribe la explicación detallada aquí...">{{ old('answer', $knowledgeBase->answer) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Etiquetas (Tags)</label>
                <input type="text" name="tags" value="{{ old('tags', is_array($knowledgeBase->tags) ? implode(', ', $knowledgeBase->tags) : '') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition-colors" placeholder="ej: cursos, inscripcion, requisitos">
                <p class="text-xs text-gray-500 mt-2 text-left">Usa comas para separar las palabras y ayudar al buscador a encontrar esta respuesta.</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.knowledge-base.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-all">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-all flex items-center gap-2 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Actualizar Entrada
                </button>
            </div>
        </form>
    </div>
</div>
@endsection