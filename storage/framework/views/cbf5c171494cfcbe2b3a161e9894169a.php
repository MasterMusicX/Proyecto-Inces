<?php $__env->startSection('title', 'Editar Usuario: ' . $user->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div class="mb-8">
        <a href="<?php echo e(route('admin.users.index')); ?>" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-3">
            ← Volver a Gestión de Usuarios
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <span class="text-3xl">✏️</span> Editar: <span class="text-blue-800 dark:text-blue-400"><?php echo e($user->name); ?></span>
        </h1>
        <p class="text-gray-500 dark:text-slate-400 mt-2">Modifica los datos personales y permisos del usuario en el sistema.</p>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden">
        
        <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>" class="p-6 sm:p-8 space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?> <?php if($errors->any()): ?>
                <div class="p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-800/50 rounded-xl mb-6">
                    <ul class="list-disc list-inside text-xs font-bold text-red-600 dark:text-red-400">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nombre Completo *</label>
                    <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Correo Electrónico *</label>
                    <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nueva Contraseña <span class="normal-case font-medium text-gray-400">(dejar en blanco para no cambiar)</span></label>
                    <input type="password" name="password" minlength="8"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Rol del Sistema *</label>
                    <select name="role" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all cursor-pointer">
                        <option value="student"    <?php echo e(old('role', $user->role) === 'student'    ? 'selected' : ''); ?>>Estudiante</option>
                        <option value="instructor" <?php echo e(old('role', $user->role) === 'instructor' ? 'selected' : ''); ?>>Instructor</option>
                        <option value="admin"      <?php echo e(old('role', $user->role) === 'admin'      ? 'selected' : ''); ?>>Administrador</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Estado de Cuenta *</label>
                    <select name="is_active" required class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all cursor-pointer">
                        <option value="1" <?php echo e(old('is_active', $user->is_active) ? 'selected' : ''); ?>>✅ Activo</option>
                        <option value="0" <?php echo e(!old('is_active', $user->is_active) ? 'selected' : ''); ?>>🚫 Inactivo</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Teléfono</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" placeholder="Ej: 0414-1234567"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Biografía / Notas</label>
                <textarea name="bio" rows="4" placeholder="Un poco sobre el usuario o notas administrativas..."
                          class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all resize-none"><?php echo e(old('bio', $user->bio)); ?></textarea>
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row justify-end gap-3">
                <a href="<?php echo e(route('admin.users.index')); ?>" class="w-full sm:w-auto text-center px-6 py-3 text-sm font-bold text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                    Cancelar
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2">
                    💾 Actualizar Usuario
                </button>
            </div>
            
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/admin/users/edit.blade.php ENDPATH**/ ?>