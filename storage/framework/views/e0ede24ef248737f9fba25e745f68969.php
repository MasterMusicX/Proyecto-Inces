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

        <div x-data="{ openNotifications: false }" class="relative">
            
            <button @click="openNotifications = !openNotifications" @click.away="openNotifications = false" 
                    class="relative p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 focus:outline-none">
                
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>

                <?php if(auth()->check() && auth()->user()->unreadNotifications->count() > 0): ?>
                    <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600 border-2 border-white dark:border-[#0f172a]"></span>
                    </span>
                <?php endif; ?>
            </button>

            <div x-show="openNotifications" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-3 w-80 sm:w-96 bg-white dark:bg-[#1e293b] rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-700/50 z-50 overflow-hidden" style="display: none;">
                
                <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700/50 flex justify-between items-center bg-gray-50/50 dark:bg-[#0f172a]/50">
                    <h3 class="font-extrabold text-gray-900 dark:text-white">Notificaciones</h3>
                    <?php if(auth()->check() && auth()->user()->unreadNotifications->count() > 0): ?>
                        <form action="<?php echo e(route('notifications.markAllRead')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 transition-colors uppercase tracking-widest">
                                Marcar todas leídas
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="max-h-80 overflow-y-auto custom-scrollbar">
                    <?php if(auth()->check() && auth()->user()->notifications->count() > 0): ?>
                        <?php $__currentLoopData = auth()->user()->notifications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('notifications.markAsRead', $notification->id)); ?>" 
                               class="block px-5 py-4 border-b border-gray-50 dark:border-slate-700/30 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors <?php echo e($notification->read_at ? 'opacity-60' : 'bg-blue-50/30 dark:bg-blue-900/10'); ?>">
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl shrink-0"><?php echo e($notification->data['icon'] ?? '📌'); ?></div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight mb-1">
                                            <?php echo e($notification->data['title'] ?? 'Nueva Notificación'); ?>

                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400 line-clamp-2">
                                            <?php echo e($notification->data['message'] ?? ''); ?>

                                        </p>
                                        <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 mt-2 block uppercase tracking-widest">
                                            <?php echo e($notification->created_at->diffForHumans()); ?>

                                        </span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="px-5 py-8 text-center">
                            <span class="text-4xl mb-2 block opacity-50">📭</span>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Todo al día</p>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">No tienes notificaciones nuevas.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="p-3 border-t border-gray-100 dark:border-slate-700/50 text-center bg-gray-50/50 dark:bg-[#0f172a]/50">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Últimas 5 notificaciones</span>
                </div>
            </div>
        </div>
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
<?php $__env->stopPush(); ?><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/components/topbar.blade.php ENDPATH**/ ?>