<?php $__env->startSection('title', $course->title); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto">
    <!-- Course Header -->
    <div class="card overflow-hidden mb-6">
        <div class="h-48 bg-gradient-to-br from-brand-600 to-blue-500 relative">
            <?php if($course->thumbnail): ?>
                <img src="<?php echo e($course->thumbnail_url); ?>" class="w-full h-full object-cover" alt="<?php echo e($course->title); ?>">
            <?php else: ?>
                <div class="absolute inset-0 flex items-center justify-center text-8xl opacity-20">📚</div>
            <?php endif; ?>
        </div>
        <div class="p-6">
            <?php if($course->category): ?>
                <span class="badge bg-brand-100 dark:bg-brand-900/30 text-brand-700 dark:text-brand-300 mb-3"><?php echo e($course->category->name); ?></span>
            <?php endif; ?>
            <h1 class="text-3xl font-display font-bold text-gray-900 dark:text-white mb-2"><?php echo e($course->title); ?></h1>
            <p class="text-gray-500 mb-4"><?php echo e($course->description); ?></p>
            <div class="flex flex-wrap gap-4 text-sm text-gray-400 mb-5">
                <span>👩‍🏫 <?php echo e($course->instructor->name); ?></span>
                <span>📊 <?php echo e($course->level_label); ?></span>
                <span>⏱️ <?php echo e($course->duration_hours); ?> horas</span>
                <span>👥 <?php echo e($course->enrolled_count); ?> inscritos</span>
                <span>📦 <?php echo e($course->modules->count()); ?> módulos</span>
            </div>
            <?php if($isEnrolled): ?>
                <a href="<?php echo e(route('student.courses.learn', $course)); ?>" class="btn-primary text-base px-7 py-3">
                    📖 Continuar Aprendiendo
                </a>
            <?php else: ?>
                <form method="POST" action="<?php echo e(route('student.courses.enroll', $course)); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-primary text-base px-7 py-3">
                        ✅ Inscribirme Gratis
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Course Content -->
        <div class="lg:col-span-2">
            <?php if($course->objectives): ?>
            <div class="card p-5 mb-5">
                <h2 class="font-display font-bold text-lg text-gray-900 dark:text-white mb-3">🎯 Objetivos del Curso</h2>
                <div class="text-sm text-gray-600 dark:text-gray-300 whitespace-pre-line"><?php echo e($course->objectives); ?></div>
            </div>
            <?php endif; ?>

            <div class="card p-5">
                <h2 class="font-display font-bold text-lg text-gray-900 dark:text-white mb-4">📋 Contenido del Curso</h2>
                <?php $__currentLoopData = $course->modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border-b border-gray-50 dark:border-gray-700 last:border-b-0 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 bg-brand-50 dark:bg-brand-900/20 text-brand-600 rounded-md flex items-center justify-center text-xs font-bold"><?php echo e($loop->iteration); ?></span>
                            <span class="font-medium text-gray-900 dark:text-white text-sm"><?php echo e($module->title); ?></span>
                        </div>
                        <span class="text-xs text-gray-400"><?php echo e($module->resources->count()); ?> recursos</span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <div class="card p-5">
                <h3 class="font-bold text-gray-900 dark:text-white mb-3">👩‍🏫 Instructor</h3>
                <div class="flex items-center gap-3">
                    <img src="<?php echo e($course->instructor->avatar_url); ?>" class="w-12 h-12 rounded-full" alt="<?php echo e($course->instructor->name); ?>">
                    <div>
                        <p class="font-semibold text-sm"><?php echo e($course->instructor->name); ?></p>
                        <?php if($course->instructor->bio): ?>
                            <p class="text-xs text-gray-400"><?php echo e(Str::limit($course->instructor->bio, 60)); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/inces-lms/resources/views/student/courses/show.blade.php ENDPATH**/ ?>