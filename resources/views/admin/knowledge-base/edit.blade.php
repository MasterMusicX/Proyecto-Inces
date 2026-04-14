@extends('layouts.app')
@section('title', 'Editar Entrada KB')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.knowledge-base.index') }}" class="text-gray-400 hover:text-gray-600">← Volver</a>
        <h1 class="text-2xl font-display font-bold text-gray-900 dark:text-white">Editar Entrada</h1>
    </div>
    <div class="card p-6">
        <form method="POST" action="{{ route('admin.knowledge-base.update', $knowledgeBase) }}" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Categoría *</label>
                    <select name="category" required class="form-input">
                        @foreach(['faq' => 'FAQ General', 'cursos' => 'Cursos', 'plataforma' => 'Plataforma', 'certificados' => 'Certificados', 'inces' => 'Sobre el INCES', 'tecnico' => 'Soporte Técnico'] as $v => $l)
                            <option value="{{ $v }}" {{ $knowledgeBase->category === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <div class="flex items-center gap-3 pb-1">
                        <input type="checkbox" name="is_active" value="1" {{ $knowledgeBase->is_active ? 'checked' : '' }} id="active" class="rounded">
                        <label for="active" class="font-medium text-sm">Entrada activa</label>
                    </div>
                </div>
            </div>
            <div>
                <label class="form-label">Pregunta *</label>
                <input name="question" value="{{ old('question', $knowledgeBase->question) }}" required class="form-input">
            </div>
            <div>
                <label class="form-label">Respuesta *</label>
                <textarea name="answer" rows="6" required class="form-input">{{ old('answer', $knowledgeBase->answer) }}</textarea>
            </div>
            <div>
                <label class="form-label">Tags (separados por coma)</label>
                <input name="tags" value="{{ old('tags', is_array($knowledgeBase->tags) ? implode(', ', $knowledgeBase->tags) : '') }}" class="form-input">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">💾 Actualizar</button>
                <a href="{{ route('admin.knowledge-base.index') }}" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
