@extends('layouts.app')
@section('title', 'Recursos del Curso')
@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-1">
                <a href="{{ route('instructor.dashboard') }}" class="hover:text-brand-500">← Dashboard</a>
            </div>
            <h1 class="text-2xl font-display font-bold text-gray-900 dark:text-white">{{ $course->title }}</h1>
            <p class="text-gray-500 text-sm">Recursos Didácticos</p>
        </div>
        <a href="{{ route('instructor.courses.resources.create', $course) }}" class="btn-primary">
            ➕ Subir Recurso
        </a>
    </div>

    @if($resources->isEmpty())
        <div class="card p-12 text-center">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="font-display font-bold text-lg text-gray-700 dark:text-gray-300 mb-2">Sin recursos aún</h3>
            <p class="text-gray-400 mb-5">Comienza subiendo materiales educativos para tus estudiantes.</p>
            <a href="{{ route('instructor.courses.resources.create', $course) }}" class="btn-primary">➕ Subir Primer Recurso</a>
        </div>
    @else
    <div class="card overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Recurso</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Módulo</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">IA</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Tamaño</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @foreach($resources as $res)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ $res->type_icon }}</span>
                            <div>
                                <p class="font-medium text-sm text-gray-900 dark:text-white">{{ $res->title }}</p>
                                @if($res->description)
                                    <p class="text-xs text-gray-400">{{ Str::limit($res->description, 50) }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500">{{ $res->module?->title ?? '—' }}</td>
                    <td class="px-5 py-4">
                        <span class="badge bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">{{ $res->type }}</span>
                    </td>
                    <td class="px-5 py-4">
                        @if($res->analysis)
                            <span class="badge {{ $res->analysis->status === 'completed' ? 'bg-green-100 text-green-700' : ($res->analysis->status === 'processing' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ match($res->analysis->status) { 'completed' => '✅ Analizado', 'processing' => '⏳ Procesando', default => '❌ Error' } }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-400">{{ $res->file_size_human }}</td>
                    <td class="px-5 py-4 text-right">
                        <form method="POST" action="{{ route('instructor.courses.resources.destroy', [$course, $res]) }}"
                            onsubmit="return confirm('¿Eliminar este recurso?')"">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-4 border-t">{{ $resources->links() }}</div>
    </div>
    @endif
</div>
@endsection
