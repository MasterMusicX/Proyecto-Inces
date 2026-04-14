<?php $__env->startSection('title', 'Editar Curso'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="<?php echo e(route('admin.courses.index')); ?>" class="text-gray-400 hover:text-gray-600">← Volver</a>
        <h1 class="text-2xl font-display font-bold text-gray-900 dark:text-white">Editar Curso</h1>
    </div>
    <div class="card p-6">
        <form method="POST" action="<?php echo e(route('admin.courses.update', $course)); ?>" enctype="multipart/form-data" class="space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <?php if($errors->any()): ?>
                <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><p>• <?php echo e($e); ?></p><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
            <div>
                <label class="form-label">Título *</label>
                <input name="title" value="<?php echo e(old('title', $course->title)); ?>" required class="form-input">
            </div>
            <div>
                <label class="form-label">Descripción *</label>
                <textarea name="description" rows="4" required class="form-input"><?php echo e(old('description', $course->description)); ?></textarea>
            </div>
            <div>
                <label class="form-label">Objetivos</label>
                <textarea name="objectives" rows="3" class="form-input"><?php echo e(old('objectives', $course->objectives)); ?></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Instructor *</label>
                    <select name="instructor_id" required class="form-input">
                        <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($i->id); ?>" <?php echo e(old('instructor_id', $course->instructor_id) == $i->id ? 'selected' : ''); ?>><?php echo e($i->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Categoría</label>
                    <select name="category_id" class="form-input">
                        <option value="">Sin categoría</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c->id); ?>" <?php echo e(old('category_id', $course->category_id) == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Nivel</label>
                    <select name="level" class="form-input">
                        <?php $__currentLoopData = ['beginner' => 'Básico', 'intermediate' => 'Intermedio', 'advanced' => 'Avanzado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v); ?>" <?php echo e(old('level', $course->level) === $v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-input">
                        <?php $__currentLoopData = ['draft' => 'Borrador', 'published' => 'Publicado', 'archived' => 'Archivado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v); ?>" <?php echo e(old('status', $course->status) === $v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Duración (horas)</label>
                    <input type="number" name="duration_hours" value="<?php echo e(old('duration_hours', $course->duration_hours)); ?>" min="0" class="form-input">
                </div>
                <div>
                    <label class="form-label">Máximo Estudiantes</label>
                    <input type="number" name="max_students" value="<?php echo e(old('max_students', $course->max_students)); ?>" min="1" class="form-input">
                </div>
            </div>
            <?php if($course->thumbnail): ?>
                <div>
                    <p class="form-label">Imagen Actual</p>
                    <img src="<?php echo e($course->thumbnail_url); ?>" class="h-28 object-cover rounded-xl mb-2" alt="<?php echo e($course->title); ?>">
                </div>
            <?php endif; ?>
            <div>
                <label class="form-label">Nueva Imagen (opcional)</label>
                <input type="file" name="thumbnail" accept="image/*" class="form-input">
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_featured" value="1" <?php echo e(old('is_featured', $course->is_featured) ? 'checked' : ''); ?> id="featured" class="rounded">
                <label for="featured" class="text-sm font-medium">Curso destacado</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">💾 Actualizar Curso</button>
                <a href="<?php echo e(route('admin.courses.index')); ?>" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/inces-lms/resources/views/admin/courses/edit.blade.php ENDPATH**/ ?>