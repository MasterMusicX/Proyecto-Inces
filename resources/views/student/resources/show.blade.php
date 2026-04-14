@extends('layouts.app')
@section('title', $resource->title)

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-400 mb-5 flex items-center gap-2">
        <a href="{{ route('student.dashboard') }}" class="hover:text-brand-500">Inicio</a> ›
        <a href="{{ route('student.courses.learn', $resource->course) }}" class="hover:text-brand-500">{{ Str::limit($resource->course->title, 30) }}</a>
        @if($resource->module) › <span>{{ $resource->module->title }}</span> @endif
        › <span class="text-gray-700 dark:text-gray-300">{{ Str::limit($resource->title, 30) }}</span>
    </nav>

    <!-- Resource Header -->
    <div class="card p-5 mb-6 flex items-start gap-5">
        <div class="w-16 h-16 bg-brand-50 dark:bg-brand-900/20 rounded-2xl flex items-center justify-center text-4xl flex-shrink-0">
            {{ $resource->type_icon }}
        </div>
        <div class="flex-1">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-display font-bold text-gray-900 dark:text-white">{{ $resource->title }}</h1>
                    @if($resource->description)
                        <p class="text-gray-500 mt-1">{{ $resource->description }}</p>
                    @endif
                </div>
                @if($resource->is_downloadable && $resource->file_url)
                <a href="{{ $resource->file_url }}" download
                    class="flex-shrink-0 btn-primary text-sm ml-4">⬇️ Descargar</a>
                @endif
            </div>
            <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-400">
                <span class="badge bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase">{{ $resource->type }}</span>
                @if($resource->file_size)<span>💾 {{ $resource->file_size_human }}</span>@endif
                <span>📅 {{ $resource->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main viewer -->
        <div class="lg:col-span-2 space-y-5">
            <div class="card overflow-hidden">
                @if($resource->type === 'pdf' && $resource->file_url)
                    <div class="p-2 bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between text-xs text-gray-500">
                        <span>📄 Visualizador PDF</span>
                        <a href="{{ $resource->file_url }}" target="_blank" class="text-brand-500 hover:underline">Abrir en nueva pestaña →</a>
                    </div>
                    <iframe src="{{ $resource->file_url }}" class="w-full" style="height:600px;" title="{{ $resource->title }}"></iframe>

                @elseif($resource->isVideo())
                    <video controls class="w-full bg-black" style="max-height:500px;">
                        <source src="{{ $resource->file_url }}">
                        <p class="p-8 text-center text-gray-400">Tu navegador no soporta reproducción de video.</p>
                    </video>

                @elseif($resource->type === 'image')
                    <div class="p-4 text-center">
                        <img src="{{ $resource->file_url }}" class="max-w-full max-h-96 mx-auto rounded-xl" alt="{{ $resource->title }}">
                    </div>

                @elseif($resource->type === 'url')
                    <div class="p-10 text-center">
                        <div class="text-5xl mb-4">🔗</div>
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Recurso Externo</h3>
                        <p class="text-gray-400 text-sm mb-5">{{ $resource->external_url }}</p>
                        <a href="{{ $resource->external_url }}" target="_blank" rel="noopener noreferrer" class="btn-primary">
                            Abrir enlace externo ↗
                        </a>
                    </div>

                @else
                    <div class="p-10 text-center">
                        <div class="text-5xl mb-4">{{ $resource->type_icon }}</div>
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ strtoupper($resource->type) }}</h3>
                        <p class="text-gray-400 text-sm mb-5">Descarga el archivo para visualizarlo con la aplicación correspondiente.</p>
                        @if($resource->is_downloadable)
                            <a href="{{ $resource->file_url }}" download class="btn-primary">⬇️ Descargar Archivo</a>
                        @else
                            <p class="text-gray-400 italic">Este recurso no está disponible para descarga.</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Ask AI about document -->
            @if($resource->isDocument())
            <div class="card p-5" x-data="docAI({{ $resource->id }})">
                <h3 class="font-display font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="text-xl">🤖</span> Pregunta sobre este documento
                </h3>
                <form @submit.prevent="ask" class="flex gap-3">
                    <input x-model="question" :disabled="loading" type="text"
                        placeholder="¿Qué quieres saber sobre este documento?"
                        class="form-input flex-1">
                    <button type="submit" :disabled="loading || !question.trim()"
                        class="btn-primary disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0">
                        <span x-show="!loading">Preguntar</span>
                        <span x-show="loading" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    </button>
                </form>
                <div x-show="answer" class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                    <p class="text-sm font-semibold text-blue-700 dark:text-blue-300 mb-2">🤖 Respuesta del Asistente:</p>
                    <p class="text-sm text-gray-700 dark:text-gray-200" x-text="answer"></p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right sidebar -->
        <div class="space-y-5">
            <!-- AI Analysis -->
            @if($resource->analysis && $resource->analysis->isCompleted())
            <div class="card p-5">
                <h3 class="font-display font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <span class="text-lg">🤖</span> Análisis Inteligente
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-4">{{ $resource->analysis->summary }}</p>

                @if(!empty($resource->analysis->keywords))
                <div class="mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">🏷️ Palabras Clave</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach(array_slice($resource->analysis->keywords, 0, 12) as $kw)
                        <span class="badge bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-300 text-xs">{{ $kw }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($resource->analysis->topics))
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">📋 Temas Principales</p>
                    <div class="space-y-1">
                        @foreach(array_slice($resource->analysis->topics, 0, 5) as $topic)
                        <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                            <span class="text-brand-400">•</span> {{ $topic }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            @elseif($resource->isDocument())
            <div class="card p-5 text-center">
                <div class="text-4xl mb-3">⏳</div>
                <p class="font-semibold text-gray-700 dark:text-gray-300 text-sm mb-1">Análisis IA pendiente</p>
                <p class="text-xs text-gray-400">El análisis automático de este documento se procesará en breve.</p>
            </div>
            @endif

            <!-- Back to course -->
            <div class="card p-4">
                <a href="{{ route('student.courses.learn', $resource->course) }}"
                    class="flex items-center gap-3 text-brand-600 dark:text-brand-400 hover:text-brand-700 transition group">
                    <span class="w-8 h-8 bg-brand-50 dark:bg-brand-900/20 rounded-xl flex items-center justify-center group-hover:bg-brand-100 transition">📚</span>
                    <div>
                        <p class="text-sm font-semibold">Volver al curso</p>
                        <p class="text-xs text-gray-400">{{ Str::limit($resource->course->title, 30) }}</p>
                    </div>
                </a>
            </div>

            <!-- Chatbot link -->
            <div class="card p-4 bg-gradient-to-br from-brand-50 to-purple-50 dark:from-brand-900/20 dark:to-purple-900/20 border-brand-100 dark:border-brand-800">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-2xl">🤖</span>
                    <div>
                        <p class="font-semibold text-sm text-gray-900 dark:text-white">¿Tienes dudas?</p>
                        <p class="text-xs text-gray-400">Consulta al Asistente Virtual</p>
                    </div>
                </div>
                <a href="{{ route('student.chatbot') }}" class="btn-primary w-full text-center text-sm">
                    Abrir Asistente IA
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function docAI(resourceId) {
    return {
        question: '',
        answer: '',
        loading: false,
        async ask() {
            if (!this.question.trim()) return;
            this.loading = true;
            this.answer = '';
            try {
                const r = await fetch(`/api/documents/${resourceId}/ask`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ question: this.question })
                });
                const data = await r.json();
                this.answer = data.success ? data.response : 'Error al procesar la pregunta.';
            } catch(e) {
                this.answer = 'Error de conexión.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush
