<?php $__env->startSection('title', 'Catálogo de Cursos'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pb-12 animate-fade-in-up">

    <div class="bg-gradient-to-r from-blue-950 to-slate-900 rounded-[2.5rem] p-10 mb-10 shadow-2xl relative overflow-hidden border border-blue-900/50">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-red-600/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-blue-600/20 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl md:text-5xl font-black text-white mb-4">Catálogo de <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-red-400 drop-shadow-sm">Cursos</span></h1>
                <p class="text-blue-100 text-lg max-w-xl">Explora y potencia tus habilidades con nuestros programas de formación técnica y profesional.</p>
            </div>
            
            <form action="<?php echo e(route('student.courses.catalog')); ?>" method="GET" class="w-full md:w-auto min-w-[300px] relative">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Buscar un curso..." 
                       class="w-full pl-5 pr-14 py-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl text-white placeholder-blue-200 focus:ring-2 focus:ring-red-500 outline-none transition-all shadow-inner">
                <button type="submit" class="absolute right-2 top-2 bottom-2 aspect-square bg-red-600 hover:bg-red-700 rounded-xl flex items-center justify-center text-white transition-colors shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <div class="flex flex-wrap gap-3 mb-10">
        <a href="<?php echo e(route('student.courses.catalog')); ?>" class="px-5 py-2.5 rounded-full text-sm font-bold transition-all <?php echo e(!request('category') ? 'bg-blue-800 dark:bg-blue-600 text-white shadow-lg shadow-blue-800/30' : 'bg-white dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-50 border border-gray-200 dark:border-slate-700'); ?>">
            Todos los Cursos
        </a>
        <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('student.courses.catalog', ['category' => $cat->slug])); ?>" 
               class="px-5 py-2.5 rounded-full text-sm font-bold transition-all <?php echo e(request('category') == $cat->slug ? 'bg-blue-800 dark:bg-blue-600 text-white shadow-lg shadow-blue-800/30' : 'bg-white dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-50 border border-gray-200 dark:border-slate-700'); ?>">
                <?php echo e($cat->icon); ?> <?php echo e($cat->name); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php $__empty_1 = true; $__currentLoopData = $courses ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="group bg-white dark:bg-[#1e293b] rounded-[2rem] overflow-hidden shadow-sm border border-gray-100 dark:border-slate-700/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col">
                
                <div class="h-52 overflow-hidden relative">
                    <img src="<?php echo e($course->thumbnail ?? 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070'); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-between p-5">
                        <div class="flex justify-between items-start">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md border border-white/30 rounded-lg text-[10px] font-bold text-white uppercase tracking-widest"><?php echo e($course->level); ?></span>
                            <?php if($course->is_featured): ?>
                                <span class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-sm shadow-lg shadow-red-600/50 text-white" title="Curso Destacado">🔥</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-[10px] font-black text-red-600 dark:text-red-400 uppercase tracking-widest block mb-2"><?php echo e($course->category->name ?? 'General'); ?></span>
                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-3 group-hover:text-blue-800 dark:group-hover:text-blue-400 transition-colors line-clamp-2"><?php echo e($course->title); ?></h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400 line-clamp-2 mb-6 flex-grow"><?php echo e($course->description); ?></p>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-slate-700/50">
                        <div class="flex items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($course->instructor->name ?? 'INCES')); ?>&background=1e40af&color=fff" class="w-8 h-8 rounded-full border-2 border-white dark:border-slate-800">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Instructor</span>
                                <span class="text-xs font-bold text-gray-700 dark:text-slate-300 line-clamp-1 max-w-[100px]"><?php echo e($course->instructor->name ?? 'INCES'); ?></span>
                            </div>
                        </div>
                        <a href="<?php echo e(route('student.courses.show', $course->slug)); ?>" class="px-5 py-2.5 bg-gray-50 hover:bg-blue-800 text-gray-700 hover:text-white dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-blue-600 dark:hover:text-white text-xs font-black rounded-xl transition-all shadow-sm">
                            Detalles &rarr;
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-16 text-center bg-white dark:bg-[#1e293b] rounded-[2rem] shadow-sm border border-gray-100 dark:border-slate-700/50">
                <div class="text-6xl mb-6">🔍</div>
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-2">No encontramos cursos</h3>
                <p class="text-gray-500 dark:text-slate-400 mb-6">Intenta buscar con otros términos o elimina los filtros.</p>
                <a href="<?php echo e(route('student.courses.catalog')); ?>" class="inline-flex items-center px-6 py-3 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20 font-bold rounded-xl transition-colors">
                    Limpiar Búsqueda
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="mt-10">
        <?php echo e($courses->links() ?? ''); ?>

    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/student/courses/catalog.blade.php ENDPATH**/ ?>