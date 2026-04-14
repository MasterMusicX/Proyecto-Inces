<?php $__env->startSection('title', 'Crear Nuevo Curso'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div class="mb-8">
        <a href="<?php echo e(route('instructor.courses.index')); ?>" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors mb-4">
            ← Volver a mis cursos
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Crear Nuevo Curso</h1>
        <p class="text-gray-500 dark:text-slate-400 mt-1">Diseña el esqueleto de tu nueva clase.</p>
    </div>

    <form action="<?php echo e(route('instructor.courses.store')); ?>" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden" x-data="{ photoPreview: null }">
        <?php echo csrf_field(); ?>

        <div class="p-8 space-y-6">
            
            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Título del Curso *</label>
                <input type="text" name="title" value="<?php echo e(old('title')); ?>" required placeholder="Ej: Carpintería Básica"
                       class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all">
                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-500 font-bold mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Categoría *</label>
                    <select name="category_id" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all cursor-pointer">
                        <option value="">Selecciona una categoría...</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-500 font-bold mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Estado Inicial *</label>
                    <select name="status" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all cursor-pointer">
                        <option value="draft" <?php echo e(old('status') == 'draft' ? 'selected' : ''); ?>>Borrador (Oculto a estudiantes)</option>
                        <option value="published" <?php echo e(old('status') == 'published' ? 'selected' : ''); ?>>Publicado (Visible)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Descripción del Curso *</label>
                <textarea name="description" rows="4" required placeholder="Explica brevemente de qué trata este curso..."
                          class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-600 transition-all resize-none"><?php echo e(old('description')); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-500 font-bold mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Imagen de Portada (Opcional)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-slate-600 border-dashed rounded-xl hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors cursor-pointer relative"
                     @click="$refs.fileInput.click()">
                    
                    <div class="space-y-1 text-center" x-show="!photoPreview">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 dark:text-slate-400 justify-center">
                            <span class="relative cursor-pointer rounded-md font-bold text-blue-600 dark:text-blue-400 hover:text-blue-500 focus-within:outline-none">
                                <span>Sube un archivo</span>
                                <input id="thumbnail" name="thumbnail" type="file" class="sr-only" x-ref="fileInput" accept="image/*"
                                       @change="const file = $event.target.files[0]; if(file) photoPreview = URL.createObjectURL(file)">
                            </span>
                            <p class="pl-1">o arrastra y suelta</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-slate-500">PNG, JPG, WEBP hasta 2MB</p>
                    </div>

                    <template x-if="photoPreview">
                        <div class="absolute inset-0 z-10 p-2">
                            <img :src="photoPreview" class="w-full h-full object-cover rounded-lg shadow-md">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity rounded-lg">
                                <span class="text-white font-bold text-sm bg-black/60 px-3 py-1 rounded-full">Cambiar foto</span>
                            </div>
                        </div>
                    </template>
                </div>
                <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-500 font-bold mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

        </div>

        <div class="px-8 py-5 bg-gray-50 dark:bg-[#0f172a]/50 border-t border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row justify-end gap-3">
            <a href="<?php echo e(route('instructor.courses.index')); ?>" class="w-full sm:w-auto text-center px-6 py-3 text-sm font-bold text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                Cancelar
            </a>
            <button type="submit" class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-white bg-red-600 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-700 rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5">
                Guardar Curso
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/inces-lms/resources/views/instructor/courses/create.blade.php ENDPATH**/ ?>