<?php $__env->startSection('title', 'Gestión de Categorías'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pb-10">
    
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Categorías de Cursos</h1>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1">Organiza el contenido de IncesCampus por áreas de formación.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6 animate-fade-in-up" style="animation-delay: 100ms;">
            
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 dark:bg-[#0f172a]/80 border-b border-gray-100 dark:border-slate-700/50 text-[11px] uppercase tracking-widest text-gray-500 dark:text-slate-400 font-bold">
                                <th class="p-4 pl-6 w-1/2">Categoría</th>
                                <th class="p-4 text-center">Cursos Asociados</th>
                                <th class="p-4 pr-6 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                            
                            <?php $__empty_1 = true; $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors group">
                                    
                                    <td class="p-4 pl-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 flex-shrink-0 transition-transform group-hover:scale-105" 
                                                 style="background-color: <?php echo e($category->color); ?>15; border-color: <?php echo e($category->color); ?>30;">
                                                <span><?php echo e($category->icon ?? '🏷️'); ?></span>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 dark:text-white text-base"><?php echo e($category->name); ?></div>
                                                <div class="text-xs text-gray-500 dark:text-slate-400 line-clamp-1 mt-0.5"><?php echo e($category->description ?? 'Sin descripción'); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300">
                                            <?php echo e($category->courses_count ?? 0); ?>

                                        </span>
                                    </td>
                                    
                                    <td class="p-4 pr-6 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
        
                                            <a href="<?php echo e(route('admin.categories.edit', $category->id)); ?>" 
                                                class="inline-flex items-center justify-center p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-xl transition-colors" title="Editar Categoría">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
        
                                            <form action="<?php echo e(route('admin.categories.destroy', $category->id)); ?>" method="POST" class="flex items-center m-0" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría? Si tiene cursos asociados, podrían quedar sin categoría.')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="inline-flex items-center justify-center p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-xl transition-colors" title="Eliminar Categoría">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
        
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="p-16 text-center">
                                        <div class="w-20 h-20 mx-auto bg-gray-50 dark:bg-[#0f172a] rounded-full flex items-center justify-center text-4xl mb-4 border border-gray-100 dark:border-slate-700/50">📭</div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Sin categorías</h3>
                                        <p class="text-gray-500 dark:text-slate-400 text-sm">Aún no has creado ninguna categoría. Usa el formulario de la derecha para agregar la primera.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>

        <div class="lg:col-span-1 animate-fade-in-up" style="animation-delay: 200ms;">
            
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 lg:sticky lg:top-24">
                
                <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-6 flex items-center gap-2 border-b border-gray-100 dark:border-slate-700/50 pb-4">
                    <span class="text-blue-500">➕</span> Nueva Categoría
                </h2>

                <form action="<?php echo e(route('admin.categories.store')); ?>" method="POST" class="space-y-5">
                    <?php echo csrf_field(); ?>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-1.5">Nombre <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required value="<?php echo e(old('name')); ?>" placeholder="Ej: Tecnología" 
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-1.5">Descripción</label>
                        <textarea name="description" rows="3" placeholder="Descripción opcional..." 
                                  class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all resize-none"><?php echo e(old('description')); ?></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-1.5">Color</label>
                            <div class="flex items-center gap-3">
                                <input type="color" name="color" value="<?php echo e(old('color', '#10b981')); ?>" 
                                       class="h-11 w-full rounded-xl cursor-pointer bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 p-1">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-1.5">Ícono (Emoji)</label>
                            <input type="text" name="icon" value="<?php echo e(old('icon', '🏷️')); ?>" placeholder="Ej: 💻" 
                                   class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-center text-xl text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-slate-700/50 mt-6">
                        <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-blue-500 hover:bg-blue-600 transition-all hover:-translate-y-0.5">
                            💾 Crear Categoría
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/admin/categories/index.blade.php ENDPATH**/ ?>