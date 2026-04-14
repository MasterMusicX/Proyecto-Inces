<header class="h-16 bg-white dark:bg-[#0f172a] border-b border-gray-200 dark:border-slate-700/50 flex items-center justify-between px-6 transition-colors z-10 shadow-sm flex-shrink-0">
            
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
        </button>
        
        <div class="hidden md:flex relative w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" placeholder="Buscar cursos, recursos..." class="w-full bg-gray-100 dark:bg-[#1e293b] border-none rounded-full pl-10 pr-4 py-2 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 transition-colors">
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button @click="darkMode = !darkMode" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 transition-colors">
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <svg x-show="darkMode" x-cloak class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        <button class="p-2 rounded-xl text-yellow-500 hover:bg-yellow-50 dark:hover:bg-slate-800 transition-colors relative">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
            <span class="absolute top-1 right-1 w-2 h-2 bg-rose-500 rounded-full"></span>
        </button>
    </div>
</header>
<?php $__env->startPush('scripts'); ?>
<script>
function searchBar() {
    return {
        q: '',
        results: null,
        async search() {
            if (this.q.length < 2) { this.results = null; return; }
            try {
                const r = await fetch(`/api/search?q=${encodeURIComponent(this.q)}`, {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                });
                const d = await r.json();
                this.results = d.success ? d.results : null;
            } catch(e) {}
        }
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/inces/Escritorio/inces-lms/resources/views/components/topbar.blade.php ENDPATH**/ ?>