<?php $__env->startSection('title', $course->title); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div class="mb-8">
        <a href="<?php echo e(route('instructor.courses.index')); ?>" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-3">
            ← Volver a Mis Cursos
        </a>
        <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">
            <?php echo e($course->title); ?>

        </h1>
        <span class="inline-block mt-3 px-3 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-xs font-black uppercase tracking-widest rounded-lg border border-red-100 dark:border-red-800/50">
            <?php echo e($course->category->name ?? 'General'); ?>

        </span>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-10">
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row items-center sm:items-start gap-4 hover:-translate-y-1 transition-transform text-center sm:text-left">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-3xl shadow-inner shrink-0 mx-auto sm:mx-0">👨🏽‍🎓</div>
            <div>
                <p class="text-3xl font-black text-gray-900 dark:text-white"><?php echo e($stats['students'] ?? 0); ?></p>
                <p class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mt-1">Estudiantes</p>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row items-center sm:items-start gap-4 hover:-translate-y-1 transition-transform text-center sm:text-left">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-500/10 text-amber-500 flex items-center justify-center text-3xl shadow-inner shrink-0 mx-auto sm:mx-0">📦</div>
            <div>
                <p class="text-3xl font-black text-gray-900 dark:text-white"><?php echo e($stats['modules'] ?? 0); ?></p>
                <p class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mt-1">Módulos</p>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row items-center sm:items-start gap-4 hover:-translate-y-1 transition-transform text-center sm:text-left">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-500/10 text-purple-500 flex items-center justify-center text-3xl shadow-inner shrink-0 mx-auto sm:mx-0">📄</div>
            <div>
                <p class="text-3xl font-black text-gray-900 dark:text-white"><?php echo e($stats['resources'] ?? 0); ?></p>
                <p class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mt-1">Recursos</p>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row items-center sm:items-start gap-4 hover:-translate-y-1 transition-transform text-center sm:text-left">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-3xl shadow-inner shrink-0 mx-auto sm:mx-0">✅</div>
            <div>
                <p class="text-3xl font-black text-gray-900 dark:text-white"><?php echo e($stats['completed'] ?? 0); ?></p>
                <p class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mt-1">Completaron</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 dark:border-slate-700/50 flex justify-between items-center bg-gray-50/50 dark:bg-[#0f172a]/50">
                <h2 class="text-lg font-extrabold text-gray-900 dark:text-white flex items-center gap-2"><span class="text-xl">👥</span> Estudiantes Recientes</h2>
                <a href="<?php echo e(route('instructor.courses.students', $course)); ?>" class="text-xs font-bold text-blue-800 dark:text-blue-400 hover:text-blue-600 transition-colors bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-800/50 px-4 py-2 rounded-xl shadow-sm">Ver todos &rarr;</a>
            </div>
            
            <div class="p-0 flex-1">
                <ul class="divide-y divide-gray-100 dark:divide-slate-700/50">
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="p-5 hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-800 text-white flex items-center justify-center font-bold text-sm shadow-sm border border-blue-900">
                                    <?php echo e(strtoupper(substr($student->name, 0, 2))); ?>

                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white text-sm"><?php echo e($student->name); ?></p>
                                    <p class="text-xs text-gray-500 dark:text-slate-400"><?php echo e($student->email); ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 w-1/3 justify-end">
                                <div class="hidden sm:block w-full h-2 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full" style="width: <?php echo e($student->pivot->progress_percentage ?? 0); ?>%"></div>
                                </div>
                                <span class="text-xs font-black text-gray-600 dark:text-slate-300"><?php echo e($student->pivot->progress_percentage ?? 0); ?>%</span>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="p-10 text-center flex flex-col items-center justify-center">
                            <span class="text-4xl mb-3 opacity-50">📭</span>
                            <p class="text-gray-500 dark:text-slate-400 text-sm font-medium">No hay estudiantes inscritos recientemente.</p>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 dark:border-slate-700/50 flex justify-between items-center bg-gray-50/50 dark:bg-[#0f172a]/50">
                <h2 class="text-lg font-extrabold text-gray-900 dark:text-white flex items-center gap-2"><span class="text-xl">📚</span> Módulos del Curso</h2>
                <a href="<?php echo e(route('instructor.courses.modules', $course)); ?>" class="text-xs font-bold text-red-600 dark:text-red-400 hover:text-red-700 transition-colors bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-800/50 px-4 py-2 rounded-xl shadow-sm">Gestionar &rarr;</a>
            </div>
            
            <div class="p-0 flex-1">
                <ul class="divide-y divide-gray-100 dark:divide-slate-700/50">
                    <?php $__empty_1 = true; $__currentLoopData = $course->modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="p-5 hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors flex items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-600 flex items-center justify-center text-xs font-black text-gray-500 dark:text-slate-400 shrink-0 shadow-inner">
                                    <?php echo e($loop->iteration); ?>

                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm line-clamp-1"><?php echo e($module->title); ?></h4>
                                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        <?php echo e($module->resources_count ?? 0); ?> recursos
                                    </p>
                                </div>
                            </div>
                            
                            <div class="shrink-0">
                                <?php if($module->is_visible): ?>
                                    <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-blue-100 dark:border-blue-800/50">
                                        Publicado
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-amber-100 dark:border-amber-800/50">
                                        Borrador
                                    </span>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="p-10 text-center flex flex-col items-center justify-center">
                            <span class="text-4xl mb-3 opacity-50">📑</span>
                            <p class="text-gray-500 dark:text-slate-400 text-sm font-medium">Aún no has creado ningún módulo.</p>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/Proyecto-Inces/resources/views/instructor/courses/show.blade.php ENDPATH**/ ?>