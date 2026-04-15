<?php $__env->startSection('title', 'Panel del Instructor'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-display font-bold text-gray-900 dark:text-white">Mi Panel</h1>
        <p class="text-gray-500 mt-1">Gestiona tus cursos y materiales educativos del Inces</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-5 hover:shadow-md transition-shadow duration-300">
            <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-3xl">📚</div>
            <div>
                <p class="text-3xl font-display font-bold text-gray-900 dark:text-white"><?php echo e($stats['total_courses']); ?></p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mis Cursos</p>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-5 hover:shadow-md transition-shadow duration-300">
            <div class="w-14 h-14 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-2xl flex items-center justify-center text-3xl">👥</div>
            <div>
                <p class="text-3xl font-display font-bold text-gray-900 dark:text-white"><?php echo e($stats['total_students']); ?></p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Estudiantes</p>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-5 hover:shadow-md transition-shadow duration-300">
            <div class="w-14 h-14 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center text-3xl">✅</div>
            <div>
                <p class="text-3xl font-display font-bold text-gray-900 dark:text-white"><?php echo e($stats['published_courses']); ?></p>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Publicados</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h2 class="font-display font-bold text-xl text-gray-900 dark:text-white">Mis Cursos</h2>
        </div>
        
        <div class="p-2">
            <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                
                <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900 dark:to-blue-800 rounded-xl flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">📚</div>
                
                <div class="flex-1">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white"><?php echo e($course->title); ?></h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-sm text-gray-500 dark:text-gray-400">👥 <?php echo e($course->enrollments_count); ?> estudiantes</span>
                        <span class="text-gray-300 dark:text-gray-600">•</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($course->level_label); ?></span>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 w-full sm:w-auto mt-4 sm:mt-0 justify-between sm:justify-end">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?php echo e($course->status === 'published' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'); ?>">
                        <?php echo e($course->status === 'published' ? 'Publicado' : 'Borrador'); ?>

                    </span>
                    
                    <a href="<?php echo e(route('instructor.courses.resources.index', $course)); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-sm font-semibold text-gray-700 dark:text-gray-300 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                        Recursos 
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-12 text-center text-gray-500 dark:text-gray-400">
                <div class="text-4xl mb-3">📭</div>
                <p class="text-lg font-medium">No tienes cursos asignados aún.</p>
                <p class="text-sm">Contacta al administrador para que te asigne uno.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/Proyecto-Inces/resources/views/instructor/dashboard.blade.php ENDPATH**/ ?>