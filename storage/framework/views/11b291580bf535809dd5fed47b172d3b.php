<?php $__env->startSection('title', 'Editar Usuario'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="<?php echo e(route('admin.users.index')); ?>" class="text-gray-400 hover:text-gray-600">← Volver</a>
        <h1 class="text-2xl font-display font-bold text-gray-900 dark:text-white">Editar: <?php echo e($user->name); ?></h1>
    </div>
    <div class="card p-6">
        <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>" class="space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <?php if($errors->any()): ?>
                <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><p>• <?php echo e($e); ?></p><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Nombre Completo *</label>
                    <input name="name" value="<?php echo e(old('name', $user->name)); ?>" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Correo Electrónico *</label>
                    <input name="email" type="email" value="<?php echo e(old('email', $user->email)); ?>" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Nueva Contraseña (dejar en blanco para no cambiar)</label>
                    <input name="password" type="password" minlength="8" class="form-input">
                </div>
                <div>
                    <label class="form-label">Confirmar Contraseña</label>
                    <input name="password_confirmation" type="password" class="form-input">
                </div>
                <div>
                    <label class="form-label">Rol *</label>
                    <select name="role" required class="form-input">
                        <option value="student"    <?php echo e(old('role', $user->role) === 'student'    ? 'selected' : ''); ?>>Estudiante</option>
                        <option value="instructor" <?php echo e(old('role', $user->role) === 'instructor' ? 'selected' : ''); ?>>Instructor</option>
                        <option value="admin"      <?php echo e(old('role', $user->role) === 'admin'      ? 'selected' : ''); ?>>Administrador</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Estado</label>
                    <select name="is_active" class="form-input">
                        <option value="1" <?php echo e(old('is_active', $user->is_active) ? 'selected' : ''); ?>>Activo</option>
                        <option value="0" <?php echo e(!old('is_active', $user->is_active) ? 'selected' : ''); ?>>Inactivo</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="form-label">Teléfono</label>
                <input name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" class="form-input">
            </div>
            <div>
                <label class="form-label">Biografía</label>
                <textarea name="bio" rows="3" class="form-input"><?php echo e(old('bio', $user->bio)); ?></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">💾 Actualizar</button>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/inces-lms/resources/views/admin/users/edit.blade.php ENDPATH**/ ?>