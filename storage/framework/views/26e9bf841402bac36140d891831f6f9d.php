<?php $__env->startSection('title', 'Módulos - ' . $course->title); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="<?php echo e(route('instructor.courses.show', $course)); ?>" class="text-gray-400 text-sm hover:text-gray-600">← Volver al curso</a>
            <h1 class="text-2xl font-display font-bold text-gray-900 dark:text-white">Módulos del Curso</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Modules List -->
        <div class="lg:col-span-2 space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card p-4 flex items-center gap-4">
                <span class="w-9 h-9 bg-brand-100 dark:bg-brand-900/30 text-brand-700 rounded-xl flex items-center justify-center font-bold text-sm flex-shrink-0"><?php echo e($loop->iteration); ?></span>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($module->title); ?></p>
                    <?php if($module->description): ?><p class="text-xs text-gray-400"><?php echo e(Str::limit($module->description, 60)); ?></p><?php endif; ?>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs text-gray-400"><?php echo e($module->resources_count); ?> recursos</span>
                        <span class="badge <?php echo e($module->is_published ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'); ?> text-xs"><?php echo e($module->is_published ? 'Publicado' : 'Borrador'); ?></span>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('instructor.courses.modules.destroy', [$course, $module])); ?>"
                    onsubmit="return confirm('¿Eliminar módulo?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="text-red-400 hover:text-red-600 text-sm">✕</button>
                </form>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card p-8 text-center text-gray-400">Sin módulos creados.</div>
            <?php endif; ?>
        </div>

        <!-- Create Module Form -->
        <div class="card p-5">
            <h2 class="font-display font-bold text-lg text-gray-900 dark:text-white mb-4">➕ Nuevo Módulo</h2>
            <form method="POST" action="<?php echo e(route('instructor.courses.modules.store', $course)); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <?php if($errors->any()): ?><div class="p-2 bg-red-50 rounded text-red-600 text-xs"><?php echo e($errors->first()); ?></div><?php endif; ?>
                <div>
                    <label class="form-label">Título *</label>
                    <input name="title" required class="form-input" placeholder="Ej: Unidad 1: Introducción">
                </div>
                <div>
                    <label class="form-label">Descripción</label>
                    <textarea name="description" rows="2" class="form-input"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_published" value="1" checked id="pub" class="rounded">
                    <label for="pub" class="text-sm font-medium">Publicar ahora</label>
                </div>
                <button type="submit" class="btn-primary w-full">💾 Crear Módulo</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/inces-lms/resources/views/instructor/courses/modules.blade.php ENDPATH**/ ?>