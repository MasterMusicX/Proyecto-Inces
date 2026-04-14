<?php $__env->startSection('title', 'Recursos del Curso'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-1">
                <a href="<?php echo e(route('instructor.dashboard')); ?>" class="hover:text-brand-500">← Dashboard</a>
            </div>
            <h1 class="text-2xl font-display font-bold text-gray-900 dark:text-white"><?php echo e($course->title); ?></h1>
            <p class="text-gray-500 text-sm">Recursos Didácticos</p>
        </div>
        <a href="<?php echo e(route('instructor.courses.resources.create', $course)); ?>" class="btn-primary">
            ➕ Subir Recurso
        </a>
    </div>

    <?php if($resources->isEmpty()): ?>
        <div class="card p-12 text-center">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="font-display font-bold text-lg text-gray-700 dark:text-gray-300 mb-2">Sin recursos aún</h3>
            <p class="text-gray-400 mb-5">Comienza subiendo materiales educativos para tus estudiantes.</p>
            <a href="<?php echo e(route('instructor.courses.resources.create', $course)); ?>" class="btn-primary">➕ Subir Primer Recurso</a>
        </div>
    <?php else: ?>
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
                <?php $__currentLoopData = $resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl"><?php echo e($res->type_icon); ?></span>
                            <div>
                                <p class="font-medium text-sm text-gray-900 dark:text-white"><?php echo e($res->title); ?></p>
                                <?php if($res->description): ?>
                                    <p class="text-xs text-gray-400"><?php echo e(Str::limit($res->description, 50)); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500"><?php echo e($res->module?->title ?? '—'); ?></td>
                    <td class="px-5 py-4">
                        <span class="badge bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs"><?php echo e($res->type); ?></span>
                    </td>
                    <td class="px-5 py-4">
                        <?php if($res->analysis): ?>
                            <span class="badge <?php echo e($res->analysis->status === 'completed' ? 'bg-green-100 text-green-700' : ($res->analysis->status === 'processing' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')); ?>">
                                <?php echo e(match($res->analysis->status) { 'completed' => '✅ Analizado', 'processing' => '⏳ Procesando', default => '❌ Error' }); ?>

                            </span>
                        <?php else: ?>
                            <span class="text-xs text-gray-400">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-400"><?php echo e($res->file_size_human); ?></td>
                    <td class="px-5 py-4 text-right">
                        <form method="POST" action="<?php echo e(route('instructor.courses.resources.destroy', [$course, $res])); ?>"
                            onsubmit="return confirm('¿Eliminar este recurso?')"">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="px-5 py-4 border-t"><?php echo e($resources->links()); ?></div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/inces-lms/resources/views/instructor/resources/index.blade.php ENDPATH**/ ?>