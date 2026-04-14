<?php $__env->startSection('title', 'Editar Entrada KB'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="<?php echo e(route('admin.knowledge-base.index')); ?>" class="text-gray-400 hover:text-gray-600">← Volver</a>
        <h1 class="text-2xl font-display font-bold text-gray-900 dark:text-white">Editar Entrada</h1>
    </div>
    <div class="card p-6">
        <form method="POST" action="<?php echo e(route('admin.knowledge-base.update', $knowledgeBase)); ?>" class="space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Categoría *</label>
                    <select name="category" required class="form-input">
                        <?php $__currentLoopData = ['faq' => 'FAQ General', 'cursos' => 'Cursos', 'plataforma' => 'Plataforma', 'certificados' => 'Certificados', 'inces' => 'Sobre el INCES', 'tecnico' => 'Soporte Técnico']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v); ?>" <?php echo e($knowledgeBase->category === $v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <div class="flex items-center gap-3 pb-1">
                        <input type="checkbox" name="is_active" value="1" <?php echo e($knowledgeBase->is_active ? 'checked' : ''); ?> id="active" class="rounded">
                        <label for="active" class="font-medium text-sm">Entrada activa</label>
                    </div>
                </div>
            </div>
            <div>
                <label class="form-label">Pregunta *</label>
                <input name="question" value="<?php echo e(old('question', $knowledgeBase->question)); ?>" required class="form-input">
            </div>
            <div>
                <label class="form-label">Respuesta *</label>
                <textarea name="answer" rows="6" required class="form-input"><?php echo e(old('answer', $knowledgeBase->answer)); ?></textarea>
            </div>
            <div>
                <label class="form-label">Tags (separados por coma)</label>
                <input name="tags" value="<?php echo e(old('tags', is_array($knowledgeBase->tags) ? implode(', ', $knowledgeBase->tags) : '')); ?>" class="form-input">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">💾 Actualizar</button>
                <a href="<?php echo e(route('admin.knowledge-base.index')); ?>" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/inces-lms/resources/views/admin/knowledge-base/edit.blade.php ENDPATH**/ ?>