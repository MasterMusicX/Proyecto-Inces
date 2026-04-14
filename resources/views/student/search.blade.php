@extends('layouts.app')
@section('title', 'Búsqueda Inteligente')

@section('content')
<div class="max-w-4xl mx-auto" x-data="searchPage()">
    <!-- Header -->
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-100 dark:bg-brand-900/30 rounded-2xl text-4xl mb-4">🔍</div>
        <h1 class="text-3xl font-display font-bold text-gray-900 dark:text-white mb-2">Búsqueda Inteligente</h1>
        <p class="text-gray-500">Busca en cursos, materiales, documentos y más</p>
    </div>

    <!-- Search Box -->
    <div class="relative mb-8">
        <input
            type="text"
            x-model="query"
            @input.debounce.500ms="doSearch()"
            @keydown.enter="doSearch()"
            placeholder="Buscar cursos, documentos, conceptos..."
            class="w-full pl-14 pr-5 py-4 text-lg border-2 border-gray-200 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-800 focus:outline-none focus:border-brand-500 transition shadow-sm"
            autofocus
        >
        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-2xl text-gray-400">🔍</span>
        <button x-show="query" @click="query = ''; results = null"
            class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xl">✕</button>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="text-center py-12">
        <div class="inline-block w-8 h-8 border-4 border-brand-500 border-t-transparent rounded-full animate-spin mb-3"></div>
        <p class="text-gray-400">Buscando con IA...</p>
    </div>

    <!-- Results -->
    <div x-show="results && !loading">
        <!-- Summary -->
        <div x-show="results" class="mb-4 text-sm text-gray-400">
            <span x-text="results ? (results.total + ' resultado(s) para &quot;' + results.query + '&quot;') : ''"></span>
        </div>

        <!-- Courses Section -->
        <template x-if="results && results.courses && results.courses.length > 0">
            <div class="mb-6">
                <h2 class="font-display font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                    <span class="text-xl">📚</span> Cursos
                </h2>
                <div class="space-y-3">
                    <template x-for="item in results.courses" :key="'c'+item.id">
                        <a :href="item.url" class="card p-4 flex items-center gap-4 hover:shadow-md transition group block">
                            <div class="w-12 h-12 bg-brand-100 dark:bg-brand-900/30 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">📚</div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white group-hover:text-brand-600 transition" x-text="item.title"></p>
                                <p class="text-sm text-gray-400 truncate" x-text="item.description"></p>
                            </div>
                            <span class="text-brand-500 group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </template>
                </div>
            </div>
        </template>

        <!-- Resources Section -->
        <template x-if="results && results.resources && results.resources.length > 0">
            <div class="mb-6">
                <h2 class="font-display font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                    <span class="text-xl">📄</span> Recursos y Documentos
                </h2>
                <div class="space-y-3">
                    <template x-for="item in results.resources" :key="'r'+item.id">
                        <a :href="item.url" class="card p-4 flex items-center gap-4 hover:shadow-md transition group block">
                            <div class="w-12 h-12 bg-orange-50 dark:bg-orange-900/20 rounded-xl flex items-center justify-center text-2xl flex-shrink-0" x-text="item.icon || '📄'"></div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white group-hover:text-brand-600 transition" x-text="item.title"></p>
                                <p class="text-sm text-gray-400 truncate" x-text="item.description"></p>
                            </div>
                            <span class="text-brand-500 group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </template>
                </div>
            </div>
        </template>

        <!-- Documents (AI analyzed) Section -->
        <template x-if="results && results.documents && results.documents.length > 0">
            <div class="mb-6">
                <h2 class="font-display font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                    <span class="text-xl">🤖</span> Resultados de Análisis IA
                </h2>
                <div class="space-y-3">
                    <template x-for="item in results.documents" :key="'d'+item.id">
                        <a :href="item.url" class="card p-4 flex items-center gap-4 hover:shadow-md transition group block border-l-4 border-purple-300 dark:border-purple-700">
                            <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/20 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">🔎</div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white group-hover:text-brand-600 transition" x-text="item.title"></p>
                                <p class="text-sm text-gray-400" x-text="item.description"></p>
                            </div>
                            <span class="text-purple-500 group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </template>
                </div>
            </div>
        </template>

        <!-- No results -->
        <template x-if="results && results.total === 0">
            <div class="text-center py-12 card p-10">
                <div class="text-5xl mb-4">🔍</div>
                <h3 class="font-display font-bold text-xl text-gray-700 dark:text-gray-300 mb-2">Sin resultados</h3>
                <p class="text-gray-400 mb-5">No se encontró nada para "<span x-text="query" class="font-medium text-gray-600 dark:text-gray-300"></span>"</p>
                <p class="text-sm text-gray-400">💡 Intenta con el <a href="{{ route('student.chatbot') }}" class="text-brand-500 hover:underline font-medium">Asistente IA</a> para preguntas más específicas.</p>
            </div>
        </template>
    </div>

    <!-- Empty State (no search yet) -->
    <div x-show="!query && !results" class="text-center py-12">
        <p class="text-gray-400 mb-6">Escribe algo para comenzar a buscar...</p>
        <div class="flex flex-wrap justify-center gap-3">
            @foreach(['Administración', 'Informática', 'Excel', 'Manual INCES', 'Seguridad'] as $suggestion)
            <button @click="query = '{{ $suggestion }}'; doSearch()"
                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl text-sm font-medium hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-brand-900/30 transition">
                {{ $suggestion }}
            </button>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function searchPage() {
    return {
        query: '',
        results: null,
        loading: false,
        async doSearch() {
            if (this.query.length < 2) { this.results = null; return; }
            this.loading = true;
            try {
                const r = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`, {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await r.json();
                this.results = data.success ? data.results : null;
            } catch(e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush
