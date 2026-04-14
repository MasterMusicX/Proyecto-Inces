@extends('layouts.app')
@section('title', 'Módulos - ' . $course->title)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-1">
                <a href="{{ route('admin.courses.index') }}" class="hover:text-brand-500">Cursos</a>
                <span>›</span>
                <span class="text-gray-600 dark:text-gray-300">{{ Str::limit($course->title, 40) }}</span>
            </div>
            <h1 class="text-2xl font-display font-bold text-gray-900 dark:text-white">Módulos del Curso</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Module List -->
        <div class="lg:col-span-2">
            <div class="card overflow-hidden">
                @forelse($modules as $module)
                <div class="flex items-center gap-4 p-4 border-b border-gray-50 dark:border-gray-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-700/20 transition" id="module-{{ $module->id }}">
                    <span class="w-8 h-8 bg-brand-100 dark:bg-brand-900/30 text-brand-700 dark:text-brand-300 rounded-xl flex items-center justify-center font-bold text-sm flex-shrink-0">{{ $loop->iteration }}</span>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $module->title }}</p>
                        @if($module->description)
                            <p class="text-xs text-gray-400">{{ Str::limit($module->description, 60) }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-xs text-gray-400">📄 {{ $module->resources_count }} recursos</span>
                            <span class="badge {{ $module->is_published ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} text-xs">
                                {{ $module->is_published ? 'Publicado' : 'Borrador' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="editModule({{ $module->id }}, {{ json_encode($module->title) }}, {{ json_encode($module->description) }}, {{ $module->is_published ? 'true' : 'false' }})"
                            class="text-brand-500 hover:text-brand-700 text-sm font-medium">Editar</button>
                        <form method="POST" action="{{ route('admin.courses.modules.destroy', [$course, $module]) }}"
                            onsubmit="return confirm('¿Eliminar este módulo y todos sus recursos?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm">✕</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-gray-400">
                    <div class="text-4xl mb-3">📭</div>
                    <p>No hay módulos creados aún.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Create/Edit Form -->
        <div>
            <div class="card p-5" x-data="{ editId: null, title: '', description: '', published: false }">
                <h2 class="font-display font-bold text-lg text-gray-900 dark:text-white mb-4" x-text="editId ? '✏️ Editar Módulo' : '➕ Nuevo Módulo'"></h2>

                <!-- Create form -->
                <form x-show="!editId" method="POST" action="{{ route('admin.courses.modules.store', $course) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label">Título *</label>
                        <input name="title" required class="form-input" placeholder="Ej: Unidad 1: Introducción">
                    </div>
                    <div>
                        <label class="form-label">Descripción</label>
                        <textarea name="description" rows="2" class="form-input" placeholder="Descripción del módulo..."></textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" value="1" id="pub_new" class="rounded">
                        <label for="pub_new" class="text-sm font-medium">Publicar inmediatamente</label>
                    </div>
                    <button type="submit" class="btn-primary w-full">💾 Crear Módulo</button>
                </form>

                <!-- Edit forms (generated per module) -->
                @foreach($modules as $module)
                <form x-show="editId === {{ $module->id }}" method="POST"
                    action="{{ route('admin.courses.modules.update', [$course, $module]) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="form-label">Título *</label>
                        <input name="title" required class="form-input" value="{{ $module->title }}">
                    </div>
                    <div>
                        <label class="form-label">Descripción</label>
                        <textarea name="description" rows="2" class="form-input">{{ $module->description }}</textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" value="1" {{ $module->is_published ? 'checked' : '' }} class="rounded">
                        <label class="text-sm font-medium">Publicado</label>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary flex-1">💾 Guardar</button>
                        <button type="button" @click="editId = null" class="btn-secondary">✕</button>
                    </div>
                </form>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editModule(id, title, description, published) {
    // Simple approach - show edit form inline via Alpine
    const event = new CustomEvent('edit-module', { detail: { id, title, description, published } });
    document.dispatchEvent(event);
}
</script>
@endpush
