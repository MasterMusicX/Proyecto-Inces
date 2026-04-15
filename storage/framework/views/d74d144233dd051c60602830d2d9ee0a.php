<?php $__env->startSection('title', 'Estudiantes - ' . $course->title); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up" 
     x-data="{ 
        showModal: false, 
        studentName: '', 
        studentId: '', 
        grade: '',
        status: 'approved',
        openModal(id, name, currentGrade, currentStatus) {
            this.studentId = id;
            this.studentName = name;
            this.grade = currentGrade || '';
            this.status = currentStatus || 'approved';
            this.showModal = true;
        }
     }">

    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="<?php echo e(route('instructor.courses.show', $course->id)); ?>" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors mb-3">
                ← Volver a Detalles del Curso
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                Estudiantes Inscritos
            </h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">Curso: <span class="font-bold text-blue-600 dark:text-blue-400"><?php echo e($course->title); ?></span></p>
        </div>
        
        <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-800/50 rounded-xl px-4 py-3 flex items-center gap-3">
            <span class="text-2xl">👥</span>
            <div>
                <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">Total Inscritos</p>
                <p class="text-xl font-black text-blue-800 dark:text-blue-400"><?php echo e($students->count()); ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-2xl overflow-hidden shadow-sm border border-gray-200 dark:border-slate-700/50 transition-colors">
        
        <div class="overflow-x-auto w-full custom-scrollbar">
            <table class="w-full text-left text-sm text-gray-600 dark:text-slate-300 whitespace-nowrap">
                <thead class="bg-gray-50 dark:bg-[#0f172a]/50 text-gray-500 dark:text-slate-400 border-b border-gray-200 dark:border-slate-700/50">
                    <tr>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs">Estudiante</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs text-center">Progreso</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs text-center">Estado</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs text-center">Nota Final</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($student->name)); ?>&background=1e40af&color=fff" class="w-10 h-10 rounded-xl shadow-sm border border-gray-200 dark:border-slate-600">
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white text-base"><?php echo e($student->name); ?></div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400 font-medium">V-<?php echo e($student->cedula ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col items-center gap-1">
                                <div class="w-24 h-2 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full" style="width: <?php echo e($student->pivot->progress_percentage ?? 0); ?>%"></div>
                                </div>
                                <span class="text-[10px] font-black text-gray-500"><?php echo e($student->pivot->progress_percentage ?? 0); ?>%</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <?php if($student->pivot->status === 'approved'): ?>
                                <span class="px-3 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-blue-200 dark:border-blue-800/50">Aprobado</span>
                            <?php elseif($student->pivot->status === 'failed'): ?>
                                <span class="px-3 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-red-200 dark:border-red-800/50">Reprobado</span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-gray-100 dark:bg-slate-700/50 text-gray-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-gray-200 dark:border-slate-600">Cursando</span>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <?php if($student->pivot->final_grade): ?>
                                <span class="text-lg font-black text-gray-900 dark:text-white"><?php echo e($student->pivot->final_grade); ?></span><span class="text-xs text-gray-500">/20</span>
                            <?php else: ?>
                                <span class="text-sm font-medium text-gray-400">-</span>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <button @click="openModal(<?php echo e($student->id); ?>, '<?php echo e($student->name); ?>', '<?php echo e($student->pivot->final_grade); ?>', '<?php echo e($student->pivot->status); ?>')" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-800 hover:bg-blue-900 dark:bg-blue-600 dark:hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-colors shadow-sm">
                                Calificar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400 font-medium">Aún no hay estudiantes inscritos en este curso.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-900/50 dark:bg-black/60 backdrop-blur-sm transition-opacity"></div>

        <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="showModal = false"
                 class="relative bg-white dark:bg-[#1e293b] rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full border border-gray-100 dark:border-slate-700">
                
                <form :action="`/instructor/courses/<?php echo e($course->id); ?>/students/${studentId}/grade`" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="px-6 pt-6 pb-6 sm:p-8">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 mb-4 mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        </div>
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-extrabold text-gray-900 dark:text-white" id="modal-title">Calificar Estudiante</h3>
                            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1" x-text="studentName"></p>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nota (0 - 20)</label>
                                <input type="number" name="final_grade" x-model="grade" min="0" max="20" step="0.1" required
                                       class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all font-bold text-lg text-center">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Estado Final</label>
                                <select name="status" x-model="status" required
                                        class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                                    <option value="in_progress">Cursando</option>
                                    <option value="approved">Aprobado</option>
                                    <option value="failed">Reprobado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 dark:bg-[#0f172a]/50 border-t border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-white bg-blue-800 hover:bg-blue-900 dark:bg-blue-600 dark:hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-800/30 transition-all hover:-translate-y-0.5">
                            Guardar Calificación
                        </button>
                        <button type="button" @click="showModal = false" class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inces/Escritorio/Proyecto-Inces/resources/views/instructor/courses/students.blade.php ENDPATH**/ ?>