<?php $__env->startSection('title', 'Editar Curso: ' . $course->title); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div class="mb-8">
        <a href="<?php echo e(route('admin.courses.index')); ?>" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-3">
            ← Volver a Gestión de Cursos
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <span class="text-3xl">📝</span> Editar: <span class="text-blue-800 dark:text-blue-400"><?php echo e($course->title); ?></span>
        </h1>
        <p class="text-gray-500 dark:text-slate-400 mt-2">Modifica la información, imagen y configuración de este curso.</p>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden">
        
        <form method="POST" action="<?php echo e(route('admin.courses.update', $course)); ?>" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            <?php echo csrf_field(); ?> 
            <?php echo method_field('PUT'); ?>

            <?php if($errors->any()): ?>
                <div class="p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-800/50 rounded-xl mb-6">
                    <ul class="list-disc list-inside text-xs font-bold text-red-600 dark:text-red-400">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Título del Curso *</label>
                <input type="text" name="title" value="<?php echo e(old('title', $course->title)); ?>" required
                       class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Descripción *</label>
                <textarea name="description" rows="4" required
                          class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all resize-none"><?php echo e(old('description', $course->description)); ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Objetivos de Aprendizaje</label>
                <textarea name="objectives" rows="3" placeholder="¿Qué aprenderá el estudiante?"
                          class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all resize-none"><?php echo e(old('objectives', $course->objectives)); ?></textarea>
            </div>

            <div class="w-full border-t border-gray-100 dark:border-slate-700/50 my-6"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Instructor *</label>
                    <select name="instructor_id" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all cursor-pointer">
                        <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($i->id); ?>" <?php echo e(old('instructor_id', $course->instructor_id) == $i->id ? 'selected' : ''); ?>><?php echo e($i->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Categoría</label>
                    <select name="category_id" class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all cursor-pointer">
                        <option value="">Sin categoría</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c->id); ?>" <?php echo e(old('category_id', $course->category_id) == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nivel</label>
                    <select name="level" class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all cursor-pointer">
                        <?php $__currentLoopData = ['beginner' => 'Básico', 'intermediate' => 'Intermedio', 'advanced' => 'Avanzado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v); ?>" <?php echo e(old('level', $course->level) === $v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Estado</label>
                    <select name="status" class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all cursor-pointer">
                        <?php $__currentLoopData = ['draft' => 'Borrador', 'published' => 'Publicado', 'archived' => 'Archivado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v); ?>" <?php echo e(old('status', $course->status) === $v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Duración (Horas)</label>
                    <input type="number" name="duration_hours" value="<?php echo e(old('duration_hours', $course->duration_hours)); ?>" min="0"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Máximo Estudiantes</label>
                    <input type="number" name="max_students" value="<?php echo e(old('max_students', $course->max_students)); ?>" min="1" placeholder="Ej: 50"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all">
                </div>
            </div>

            <div class="w-full border-t border-gray-100 dark:border-slate-700/50 my-6"></div>

            <div class="flex flex-col md:flex-row gap-6">
                <?php if($course->thumbnail): ?>
                    <div class="shrink-0">
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Imagen Actual</label>
                        <div class="w-40 h-28 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-slate-700">
                            <img src="<?php echo e($course->thumbnail_url); ?>" class="w-full h-full object-cover" alt="<?php echo e($course->title); ?>">
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nueva Imagen de Portada (Opcional)</label>
                    <input type="file" name="thumbnail" accept="image/*"
                           class="block w-full text-sm text-gray-500 dark:text-slate-400
                                  file:mr-4 file:py-3 file:px-4
                                  file:rounded-xl file:border-0
                                  file:text-sm file:font-bold
                                  file:bg-blue-50 file:text-blue-800
                                  hover:file:bg-blue-100 transition-all
                                  dark:file:bg-blue-900/30 dark:file:text-blue-400 dark:hover:file:bg-blue-900/50 cursor-pointer">
                    <p class="text-[10px] text-gray-400 mt-2">Formatos aceptados: JPG, PNG. Tamaño máximo recomendado: 2MB.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-slate-700 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors mt-6" onclick="document.getElementById('featured').click()">
                <input type="checkbox" name="is_featured" value="1" <?php echo e(old('is_featured', $course->is_featured) ? 'checked' : ''); ?> id="featured" 
                       class="w-5 h-5 text-blue-800 bg-white border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                <div class="flex-1 select-none">
                    <label for="featured" class="text-sm font-bold text-gray-900 dark:text-white cursor-pointer block">
                        Marcar como Curso Destacado
                    </label>
                    <p class="text-xs text-gray-500 dark:text-slate-400">El curso aparecerá en la sección principal del panel de los estudiantes.</p>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row justify-end gap-3">
                <a href="<?php echo e(route('admin.courses.index')); ?>" class="w-full sm:w-auto text-center px-6 py-3 text-sm font-bold text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                    Cancelar
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2">
                    💾 Actualizar Curso
                </button>
            </div>
            
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/admin/courses/edit.blade.php ENDPATH**/ ?>