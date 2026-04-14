<header class="flex items-center justify-between px-6 py-4 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm">
    <!-- Mobile menu button -->
    <button class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <!-- Search -->
    <div class="hidden md:flex flex-1 max-w-md" x-data="searchbar()">
        <div class="relative w-full">
            <input
                type="text"
                x-model="query"
                @input.debounce.400ms="search()"
                @keydown.escape="results = null"
                placeholder="Buscar cursos, recursos..."
                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition"
            >
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            <!-- Search results dropdown -->
            <div x-show="results && query.length > 1" x-transition
                class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 z-50 max-h-80 overflow-y-auto">
                <template x-if="results && results.total > 0">
                    <div class="p-2">
                        <template x-for="item in [...(results.courses || []), ...(results.resources || [])]" :key="item.id + item.type">
                            <a :href="item.url" class="flex items-center gap-3 p-2.5 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                                <span class="text-lg" x-text="item.type === 'course' ? '📚' : (item.icon || '📄')"></span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200" x-text="item.title"></p>
                                    <p class="text-xs text-gray-400" x-text="item.description"></p>
                                </div>
                            </a>
                        </template>
                    </div>
                </template>
                <template x-if="results && results.total === 0">
                    <div class="p-4 text-center text-gray-400 text-sm">Sin resultados para "<span x-text="query"></span>"</div>
                </template>
            </div>
        </div>
    </div>

    <!-- Right side -->
    <div class="flex items-center gap-3 ml-4">
        <!-- Dark mode toggle -->
        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
            class="p-2 rounded-xl text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            <span x-show="!darkMode">🌙</span>
            <span x-show="darkMode">☀️</span>
        </button>

        <!-- User avatar -->
        <div class="flex items-center gap-3">
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full border-2 border-brand-100">
            <div class="hidden md:block">
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script>
function searchbar() {
    return {
        query: '',
        results: null,
        async search() {
            if (this.query.length < 2) { this.results = null; return; }
            const r = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`, {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            const data = await r.json();
            this.results = data.success ? data.results : null;
        }
    }
}
</script>
@endpush
