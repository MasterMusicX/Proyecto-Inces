<?php $__env->startSection('title', 'Subir Recurso'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto py-6">
    
    <div class="mb-6 flex items-center gap-3">
        <a href="<?php echo e(route('instructor.courses.resources.index', $course)); ?>" class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white flex items-center gap-1 text-sm font-medium transition-colors">
            &larr; Volver
        </a>
        <h1 class="text-2xl font-display font-bold text-gray-900 dark:text-white">Subir Recurso</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden p-6 sm:p-8">
        
        
        <form method="POST" action="<?php echo e(route('instructor.courses.resources.store', $course)); ?>"
            enctype="multipart/form-data" class="space-y-6" 
            x-data="{ type: '<?php echo e(old('type', 'pdf')); ?>', fileName: 'Sin archivos seleccionados' }">
            
            <?php echo csrf_field(); ?>
            
            <?php if($errors->any()): ?>
                <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl text-red-600 dark:text-red-400 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($e); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Tipo de Recurso *</label>
                <select name="type" x-model="type" required class="w-full sm:w-1/2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="pdf">📄 PDF</option>
                    <option value="docx">📝 Word (DOCX)</option>
                    <option value="xlsx">📊 Excel (XLSX)</option>
                    <option value="pptx">📋 PowerPoint (PPTX)</option>
                    <option value="video">🎬 Video</option>
                    <option value="image">🖼️ Imagen</option>
                    <option value="url">🔗 URL Externa</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Título *</label>
                <input type="text" name="title" value="<?php echo e(old('title')); ?>" required placeholder="Nombre del recurso" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                <textarea name="description" rows="3" placeholder="Descripción opcional..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"><?php echo e(old('description')); ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Módulo</label>
                <select name="module_id" class="w-full sm:w-1/2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Sin módulo específico</option>
                    <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m->id); ?>" <?php echo e(old('module_id') == $m->id ? 'selected' : ''); ?>><?php echo e($m->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div x-show="type !== 'url'" x-transition>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Archivo *</label>
                <div class="flex items-center gap-3">
                    <label class="cursor-pointer bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-500 text-sm font-medium transition-colors">
                        Seleccionar archivo
                        <input type="file" name="file" class="hidden" x-ref="fileInput" 
                            @change="fileName = $refs.fileInput.files[0] ? $refs.fileInput.files[0].name : 'Sin archivos seleccionados'"
                            :accept="type === 'pdf' ? '.pdf' : (type === 'docx' ? '.docx' : (type === 'xlsx' ? '.xlsx' : (type === 'pptx' ? '.pptx' : (type === 'video' ? '.mp4,.avi,.mov,.webm' : 'image/*'))))">
                    </label>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium truncate max-w-xs" x-text="fileName"></span>
                </div>
                <p class="text-xs text-gray-400 mt-2">Máximo 50 MB</p>
            </div>

            <div x-show="type === 'url'" x-transition>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">URL Externa *</label>
                <input type="url" name="external_url" value="<?php echo e(old('external_url')); ?>" placeholder="https://..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>

            <div class="flex items-center pt-2">
                <input type="checkbox" name="is_downloadable" value="1" checked id="downloadable" class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700">
                <label for="downloadable" class="ml-2 block text-sm font-bold text-gray-900 dark:text-gray-200">
                    Permitir descarga
                </label>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 rounded-xl p-4 flex gap-3 mt-4">
                <div class="text-2xl">🤖</div>
                <div>
                    <p class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-1">Análisis IA:</p>
                    <p class="text-sm text-blue-600 dark:text-blue-400 leading-relaxed">
                        Los documentos (PDF, Word, Excel, PowerPoint) serán analizados automáticamente por IA para generar resúmenes y palabras clave.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 mt-6 border-t border-gray-100 dark:border-gray-700">
                <button type="submit" class="bg-[#0f4b7a] hover:bg-[#0a3556] text-white px-6 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Subir Recurso
                </button>
                <a href="<?php echo e(route('instructor.courses.resources.index', $course)); ?>" class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-colors">
                    Cancelar
                </a>
            </div>
            
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/instructor/resources/create.blade.php ENDPATH**/ ?>