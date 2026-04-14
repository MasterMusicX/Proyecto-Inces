<?php $__env->startSection('title', $course->title); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="card p-5 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-brand-500 to-blue-600 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">📚</div>
            <div>
                <h1 class="text-xl font-display font-bold text-gray-900 dark:text-white"><?php echo e($course->title); ?></h1>
                <p class="text-gray-400 text-sm">Instructor: <?php echo e($course->instructor->name); ?></p>
            </div>
            <a href="<?php echo e(route('student.chatbot')); ?>" class="ml-auto btn-primary text-sm">
                🤖 Asistente IA
            </a>
        </div>
    </div>

    <!-- Modules and Resources -->
    <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $course->modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card overflow-hidden" x-data="{ open: true }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between p-5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-brand-100 dark:bg-brand-900/30 text-brand-700 rounded-xl flex items-center justify-center font-bold text-sm">
                        <?php echo e($loop->iteration); ?>

                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white"><?php echo e($module->title); ?></h3>
                    <span class="badge bg-gray-100 dark:bg-gray-700 text-gray-500"><?php echo e($module->resources->count()); ?> recursos</span>
                </div>
                <span class="text-gray-400" x-text="open ? '▲' : '▼'"></span>
            </button>

            <div x-show="open" x-transition class="border-t border-gray-50 dark:border-gray-700">
                <?php $__empty_2 = true; $__currentLoopData = $module->resources->where('is_published', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                <a href="<?php echo e(route('student.resources.show', $resource)); ?>"
                    class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/20 transition border-b border-gray-50 dark:border-gray-700/50 last:border-b-0">
                    <span class="text-xl flex-shrink-0"><?php echo e($resource->type_icon); ?></span>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm text-gray-800 dark:text-gray-200 truncate"><?php echo e($resource->title); ?></p>
                        <?php if($resource->description): ?>
                            <p class="text-xs text-gray-400 truncate"><?php echo e($resource->description); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="text-xs text-gray-400 uppercase font-medium"><?php echo e($resource->type); ?></span>
                        <?php if($resource->file_size): ?>
                            <span class="text-xs text-gray-300"><?php echo e($resource->file_size_human); ?></span>
                        <?php endif; ?>
                        <span class="text-brand-500">→</span>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                <p class="px-5 py-4 text-sm text-gray-400 italic">Este módulo aún no tiene recursos publicados.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card p-12 text-center">
            <div class="text-5xl mb-4">📭</div>
            <p class="text-gray-400">Este curso aún no tiene módulos disponibles.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/inces-lms/resources/views/student/courses/learn.blade.php ENDPATH**/ ?>