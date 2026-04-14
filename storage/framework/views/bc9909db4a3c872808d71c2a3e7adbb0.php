<?php $__env->startSection('title', $course->title); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="<?php echo e(route('instructor.courses.index')); ?>" class="text-gray-400 hover:text-gray-600">← Mis Cursos</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-xl font-display font-bold text-gray-900 dark:text-white"><?php echo e($course->title); ?></h1>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <?php $__currentLoopData = [['🎓', 'Estudiantes', $stats['students']], ['📦', 'Módulos', $stats['modules']], ['📄', 'Recursos', $stats['resources']], ['✅', 'Completaron', $stats['completed']]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $label, $value]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card p-4 text-center">
            <div class="text-3xl mb-1"><?php echo e($icon); ?></div>
            <p class="text-2xl font-display font-bold text-gray-900 dark:text-white"><?php echo e($value); ?></p>
            <p class="text-xs text-gray-400"><?php echo e($label); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Students -->
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-display font-bold text-gray-900 dark:text-white">Estudiantes Recientes</h2>
                <a href="<?php echo e(route('instructor.courses.students', $course)); ?>" class="text-brand-500 text-sm hover:underline">Ver todos →</a>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 dark:border-gray-700 last:border-b-0">
                <img src="<?php echo e($student->avatar_url); ?>" class="w-8 h-8 rounded-full" alt="<?php echo e($student->name); ?>">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate"><?php echo e($student->name); ?></p>
                </div>
                <div class="text-xs text-right">
                    <p class="font-bold text-brand-600"><?php echo e($student->pivot->progress_percentage); ?>%</p>
                    <div class="w-16 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full mt-0.5">
                        <div class="h-full bg-brand-500 rounded-full" style="width:<?php echo e($student->pivot->progress_percentage); ?>%"></div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-gray-400 text-sm py-4 text-center">Sin estudiantes inscritos aún.</p>
            <?php endif; ?>
        </div>

        <!-- Modules overview -->
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-display font-bold text-gray-900 dark:text-white">Módulos</h2>
                <a href="<?php echo e(route('instructor.courses.modules', $course)); ?>" class="text-brand-500 text-sm hover:underline">Gestionar →</a>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $course->modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 dark:border-gray-700 last:border-b-0">
                <span class="w-7 h-7 bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg flex items-center justify-center text-xs font-bold"><?php echo e($loop->iteration); ?></span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate"><?php echo e($module->title); ?></p>
                    <p class="text-xs text-gray-400"><?php echo e($module->resources->count()); ?> recursos</p>
                </div>
                <span class="badge <?php echo e($module->is_published ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'); ?> text-xs">
                    <?php echo e($module->is_published ? '✓' : 'Borrador'); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-gray-400 text-sm py-4 text-center">Sin módulos creados.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/inces-lms/resources/views/instructor/courses/show.blade.php ENDPATH**/ ?>