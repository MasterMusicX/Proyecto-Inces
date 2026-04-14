<?php $__env->startSection('title', 'Crear Nuevo Curso'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto pb-10">
    
    <div class="flex items-center justify-between mb-8 animate-fade-in-up">
        <div>
            <a href="<?php echo e(route('admin.courses.index')); ?>" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 transition-colors mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a Cursos
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Crear Nuevo Curso</h1>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden animate-fade-in-up" style="animation-delay: 100ms;">
        
        <form action="<?php echo e(route('admin.courses.store')); ?>" method="POST" enctype="multipart/form-data" class="p-6 md:p-10 space-y-8">
            <?php echo csrf_field(); ?>

            <div class="space-y-6">
                <h2 class="text-lg font-black text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700/50 pb-2 uppercase tracking-wider">
                    Información General
                </h2>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Título del Curso <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required value="<?php echo e(old('title')); ?>" placeholder="Ej: Introducción a la Electricidad Básica" 
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Categoría <span class="text-rose-500">*</span></label>
                        <select name="category_id" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer">
                            <option value="">Seleccionar Categoría...</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Instructor <span class="text-rose-500">*</span></label>
                        <select name="instructor_id" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer">
                            <option value="">Seleccionar Instructor...</option>
                            <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($instructor->id); ?>" <?php echo e(old('instructor_id') == $instructor->id ? 'selected' : ''); ?>><?php echo e($instructor->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Descripción del Curso <span class="text-rose-500">*</span></label>
                    <textarea name="description" required rows="4" placeholder="Explica de qué trata este curso..." 
                              class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all resize-none"><?php echo e(old('description')); ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Objetivos (Opcional)</label>
                    <textarea name="objectives" rows="3" placeholder="Al finalizar el curso, el participante será capaz de..." 
                              class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all resize-none"><?php echo e(old('objectives')); ?></textarea>
                </div>
            </div>

            <div class="space-y-6 pt-4">
                <h2 class="text-lg font-black text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700/50 pb-2 uppercase tracking-wider">
                    Detalles de Configuración
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Nivel</label>
                        <select name="level" class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer">
                            <option value="basico" <?php echo e(old('level') == 'basico' ? 'selected' : ''); ?>>🟢 Básico</option>
                            <option value="intermedio" <?php echo e(old('level') == 'intermedio' ? 'selected' : ''); ?>>🟡 Intermedio</option>
                            <option value="avanzado" <?php echo e(old('level') == 'avanzado' ? 'selected' : ''); ?>>🔴 Avanzado</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Estado</label>
                        <select name="status" class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer">
                            <option value="draft" <?php echo e(old('status') == 'draft' ? 'selected' : ''); ?>>📝 Borrador</option>
                            <option value="published" <?php echo e(old('status') == 'published' ? 'selected' : ''); ?>>✅ Publicado</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Duración (Horas)</label>
                        <input type="number" name="duration_hours" min="0" value="<?php echo e(old('duration_hours', 0)); ?>" 
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Max. Estudiantes</label>
                        <input type="number" name="max_students" min="1" value="<?php echo e(old('max_students')); ?>" placeholder="Vacío = Ilimitado" 
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class="space-y-6 pt-4">
                <h2 class="text-lg font-black text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700/50 pb-2 uppercase tracking-wider">
                    Multimedia y Destacados
                </h2>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Imagen de Portada</label>
                    <input type="file" name="thumbnail" accept="image/*" 
                           class="w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-500/20 dark:file:text-blue-400 dark:hover:file:bg-blue-500/30 transition-all cursor-pointer border border-gray-200 dark:border-slate-700 rounded-xl bg-gray-50 dark:bg-[#0f172a]">
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-2">Formatos recomendados: JPG, PNG. Tamaño ideal: 1280x720px.</p>
                </div>

                <div class="pt-4">
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" class="sr-only peer" <?php echo e(old('is_featured') ? 'checked' : ''); ?>>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-500"></div>
                        <span class="ml-4 text-sm font-bold text-gray-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">🔥 Marcar como Curso Destacado (Aparecerá en el inicio)</span>
                    </label>
                </div>
            </div>

            <div class="pt-8 flex items-center justify-end gap-4 border-t border-gray-100 dark:border-slate-700/50">
                <a href="<?php echo e(route('admin.courses.index')); ?>" class="px-6 py-3.5 text-sm font-bold text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-8 py-3.5 text-sm font-bold text-white bg-blue-500 hover:bg-blue-600 rounded-xl shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Guardar y Crear Curso
                </button>
            </div>

        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/admin/courses/create.blade.php ENDPATH**/ ?>