<?php $__env->startSection('title', 'Base de Conocimiento IA'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pb-10">
    
    <div class="flex items-center justify-between mb-8 animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Base de Conocimiento IA</h1>
            <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1">El Chatbot consulta esta base para responder preguntas frecuentes.</p>
        </div>
        <a href="<?php echo e(route('admin.knowledge-base.create')); ?>" class="inline-flex items-center px-6 py-3 text-sm font-bold text-white bg-blue-500 hover:bg-blue-600 rounded-xl shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Entrada
        </a>
    </div>

    <div class="bg-white dark:bg-[#1e293b] p-4 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 mb-8 animate-fade-in-up" style="animation-delay: 100ms;">
        <form action="<?php echo e(route('admin.knowledge-base.index')); ?>" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">🔍</div>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Buscar por pregunta o palabras clave..." 
                       class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
            <select name="category" class="md:w-48 bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer">
                <option value="">Todas las categorías</option>
                <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat); ?>" <?php echo e(request('category') == $cat ? 'selected' : ''); ?>><?php echo e(ucfirst($cat)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="px-6 py-3 bg-gray-900 dark:bg-slate-700 hover:bg-gray-800 dark:hover:bg-slate-600 text-white font-bold rounded-xl text-sm transition-colors">
                Filtrar
            </button>
            <?php if(request()->anyFilled(['search', 'category'])): ?>
                <a href="<?php echo e(route('admin.knowledge-base.index')); ?>" class="px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-xl text-sm font-bold text-center transition-colors">
                    Limpiar
                </a>
            <?php endif; ?>
        </form>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden animate-fade-in-up" style="animation-delay: 200ms;">
        
        <?php $__empty_1 = true; $__currentLoopData = $entries ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-6 border-b border-gray-100 dark:border-slate-700/50 last:border-b-0 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors group">
                <div class="flex items-start gap-5">
                    
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 flex-shrink-0 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
                        
                        💡
                    </div>

                    <div class="flex-grow">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <?php echo e($entry->question); ?>

                            </h3>
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                <a href="<?php echo e(route('admin.knowledge-base.edit', $entry->id)); ?>" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-xl transition-colors" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="<?php echo e(route('admin.knowledge-base.destroy', $entry->id)); ?>" method="POST" class="inline m-0" onsubmit="return confirm('¿Seguro que deseas eliminar esta pregunta?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-xl transition-colors" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-slate-400 mt-1 mb-4 line-clamp-2">
                            <?php echo e(Str::limit($entry->answer, 200)); ?>

                        </p>

                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs font-medium text-gray-500 dark:text-slate-500 border-t border-gray-100 dark:border-slate-700/50 pt-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 font-bold uppercase tracking-wider text-[10px]">
                                <?php echo e($entry->category ?? 'General'); ?>

                            </span>
                            <span class="flex items-center gap-1.5">👁️ <?php echo e($entry->views ?? 0); ?> vistas</span>
                            <span class="flex items-center gap-1.5">📅 Actualizado: <?php echo e($entry->updated_at->diffForHumans()); ?></span>
                            <?php if($entry->tags): ?>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    🏷️
                                    <?php $__currentLoopData = $entry->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="px-2 py-0.5 bg-gray-100 dark:bg-slate-700 rounded-md"><?php echo e($tag); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-16 text-center">
                <div class="w-20 h-20 mx-auto bg-gray-50 dark:bg-[#0f172a] rounded-full flex items-center justify-center text-4xl mb-4 border border-gray-100 dark:border-slate-700/50">📭</div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Base de conocimiento vacía</h3>
                <p class="text-gray-500 dark:text-slate-400 text-sm">Aún no hay preguntas registradas. ¡Crea la primera para alimentar a la IA!</p>
            </div>
        <?php endif; ?>

    </div>
    
    <div class="mt-8">
        <?php echo e($entries->links()); ?>

    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/admin/knowledge-base/index.blade.php ENDPATH**/ ?>