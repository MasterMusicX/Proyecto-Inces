@extends('layouts.app')
@section('title', 'Estudiantes - ' . $course->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up" 
     x-data="{ 
        showModal: false, 
        studentName: '', 
        studentId: '', 
        status: 'in_progress',
        moduleGrades: {},
        expandedStudent: null,
        
        get averageGrade() {
            let total = 0;
            let count = 0;
            for(let key in this.moduleGrades) {
                let val = parseFloat(this.moduleGrades[key]);
                if(!isNaN(val) && val >= 0) {
                    total += val;
                    count++;
                }
            }
            return count > 0 ? (total / count).toFixed(2) : '';
        },

        openModal(id, name, currentStatus, gradesData, finalGradeFallback) {
            this.studentId = id;
            this.studentName = name;
            this.status = currentStatus || 'in_progress';
            this.moduleGrades = gradesData && Object.keys(gradesData).length > 0 ? gradesData : {};
            if(Object.keys(this.moduleGrades).length === 0 && finalGradeFallback) {
                this.moduleGrades['final'] = finalGradeFallback;
            }
            this.showModal = true;
        }
     }">

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('instructor.courses.show', $course->id) }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-3 group">
                <svg class="w-4 h-4 mr-1.5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Volver a Detalles del Curso
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                Estudiantes Inscritos y Aprobación de Módulos
            </h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">Curso: <span class="font-bold text-blue-600 dark:text-blue-400">{{ $course->title }}</span></p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <a href="{{ route('instructor.courses.export-students', $course->id) }}" target="_blank" class="w-full sm:w-auto px-5 py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Lista de Asistencia
            </a>

            <div class="w-full sm:w-auto bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-800/50 rounded-xl px-5 py-3 flex items-center justify-center gap-4 shadow-sm">
                <div class="text-blue-500 dark:text-blue-400 shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">Total Inscritos</p>
                    <p class="text-xl font-black text-blue-800 dark:text-blue-400 leading-none mt-1">{{ $students->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 font-bold text-sm flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-[#1e293b] rounded-3xl overflow-hidden shadow-sm border border-gray-200 dark:border-slate-700/50 transition-colors">
        <div class="overflow-x-auto w-full custom-scrollbar">
            <table class="w-full text-left text-sm text-gray-600 dark:text-slate-300 whitespace-nowrap">
                <thead class="bg-gray-50 dark:bg-[#0f172a]/50 text-gray-500 dark:text-slate-400 border-b border-gray-200 dark:border-slate-700/50">
                    <tr>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs">Estudiante</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs text-center">Progreso</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs text-center">Aprobación de Módulos</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs text-center">Estado</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs text-center">Nota Final</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                    @forelse($students as $student)
                        @php
                            $approvedModulesIds = $student->moduleApprovals->where('is_approved', true)->pluck('module_id')->toArray();
                            $totalCourseModules = $course->modules->count();
                            $approvedCount = count($approvedModulesIds);
                        @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @php
                                    $avatar = $student->avatar;
                                    $avatarUrl = $avatar 
                                        ? (str_starts_with($avatar, 'http') ? $avatar : asset('storage/' . $avatar))
                                        : 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=1e40af&color=fff&bold=true';
                                @endphp
                                <img src="{{ $avatarUrl }}" class="w-10 h-10 rounded-xl object-cover shadow-sm border border-gray-200 dark:border-slate-600">
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white text-base">{{ $student->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-0.5">V-{{ $student->cedula ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col items-center gap-1.5">
                                <div class="w-24 h-2 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full transition-all duration-500" style="width: {{ $student->pivot->progress_percentage ?? 0 }}%"></div>
                                </div>
                                <span class="text-[10px] font-black text-gray-500">{{ $student->pivot->progress_percentage ?? 0 }}%</span>
                            </div>
                        </td>

                        {{-- APROBACIÓN POR MÓDULOS --}}
                        <td class="px-6 py-4 text-center">
                            <button @click="expandedStudent = expandedStudent === {{ $student->id }} ? null : {{ $student->id }}"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 rounded-xl text-xs font-bold border border-indigo-200 dark:border-indigo-800/40 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-all">
                                <span>Módulos: <strong>{{ $approvedCount }}/{{ $totalCourseModules }}</strong></span>
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': expandedStudent === {{ $student->id }} }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($student->pivot->status === 'approved')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-green-200 dark:border-green-800/50">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                    Aprobado
                                </span>
                            @elseif($student->pivot->status === 'failed')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-red-200 dark:border-red-800/50">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Reprobado
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-slate-700/50 text-gray-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-gray-200 dark:border-slate-600">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Cursando
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($student->pivot->final_grade)
                                <div class="flex items-baseline justify-center text-gray-900 dark:text-white">
                                    <span class="text-lg font-black">{{ $student->pivot->final_grade }}</span>
                                    <span class="text-xs text-gray-500 font-bold ml-0.5">/20</span>
                                </div>
                            @else
                                <span class="text-xs font-bold text-gray-400 dark:text-slate-600 px-2 py-1 border border-dashed border-gray-300 dark:border-slate-600 rounded-md">Sin nota</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            @php
                                $gradesJson = json_encode($student->pivot->module_grades ?? (object)[]);
                            @endphp
                            
                            <button @click="openModal({{ $student->id }}, '{{ addslashes($student->name) }}', '{{ $student->pivot->status }}', {{ $gradesJson }}, '{{ $student->pivot->final_grade }}')" 
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 hover:bg-blue-600 dark:bg-blue-500/10 dark:hover:bg-blue-600 text-blue-700 hover:text-white dark:text-blue-400 font-bold text-xs rounded-xl transition-colors border border-blue-200 dark:border-blue-800 hover:border-transparent">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                Calificar
                            </button>
                        </td>
                    </tr>

                    {{-- FILA DESPLEGABLE DE GESTIÓN DE MÓDULOS --}}
                    <tr x-show="expandedStudent === {{ $student->id }}" x-collapse class="bg-gray-50/80 dark:bg-slate-900/60">
                        <td colspan="6" class="px-8 py-4">
                            <div class="bg-white dark:bg-[#1e293b] p-4 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-inner space-y-3">
                                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-700 pb-2">
                                    <h4 class="text-xs font-black text-gray-700 dark:text-slate-300 uppercase tracking-wider">
                                        Gestión de Módulos para: <span class="text-blue-600 dark:text-blue-400">{{ $student->name }}</span>
                                    </h4>
                                    <span class="text-[11px] font-bold text-gray-500">
                                        Haz clic en un módulo para cambiar el dictamen de aprobación
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @forelse($course->modules as $modIndex => $module)
                                        @php
                                            $isApproved = in_array($module->id, $approvedModulesIds);
                                        @endphp
                                        <form action="{{ route('instructor.courses.students.modules.toggle', [$course->id, $student->id, $module->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full text-left p-3 rounded-xl border transition-all flex items-center justify-between gap-3 shadow-sm {{ $isApproved ? 'bg-green-50/60 dark:bg-green-500/10 border-green-300 dark:border-green-700/50 hover:bg-green-100/70' : 'bg-gray-50 dark:bg-slate-800/40 border-gray-200 dark:border-slate-700 hover:bg-gray-100' }}">
                                                <div class="min-w-0 flex-1">
                                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Módulo {{ $modIndex + 1 }}</span>
                                                    <p class="text-xs font-bold text-gray-800 dark:text-slate-200 truncate">{{ $module->title }}</p>
                                                </div>
                                                <div class="shrink-0">
                                                    @if($isApproved)
                                                        <span class="px-2.5 py-1 bg-green-500 text-white font-black text-[10px] uppercase rounded-lg shadow-sm flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                                            Aprobado
                                                        </span>
                                                    @else
                                                        <span class="px-2.5 py-1 bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/30 font-black text-[10px] uppercase rounded-lg">
                                                            Aprobar Módulo
                                                        </span>
                                                    @endif
                                                </div>
                                            </button>
                                        </form>
                                    @empty
                                        <p class="text-xs text-gray-500 italic">No hay módulos creados en este curso.</p>
                                    @endforelse
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16">
                            <div class="flex flex-col items-center justify-center text-gray-400 dark:text-slate-500">
                                <svg class="w-16 h-16 mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                                <span class="font-medium text-sm">Aún no hay estudiantes inscritos en este curso.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL DE CALIFICACIÓN POR MÓDULOS Y NOTA FINAL --}}
    <div x-show="showModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-900/60 dark:bg-black/70 backdrop-blur-sm transition-opacity"></div>

        <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="showModal = false"
                 class="relative bg-white dark:bg-[#1e293b] rounded-3xl text-left shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full border border-gray-100 dark:border-slate-700 flex flex-col max-h-[90vh]">
                
                <form :action="'/instructor/courses/{{ $course->id }}/students/' + studentId + '/grade'" method="POST" class="flex flex-col h-full overflow-hidden">
                    @csrf
                    
                    <div class="px-6 pt-6 pb-4 sm:px-8 sm:pt-8 shrink-0 border-b border-gray-100 dark:border-slate-700/50">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 mb-4 mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25v-.008zM12 8.25h.008v.008H12v-.008zm0 2.25h.008v.008H12v-.008zm0 2.25h.008v.008H12v-.008zm0 2.25h.008v.008H12v-.008zm0 2.25h.008v.008H12v-.008zM15.75 8.25h.008v.008H15.75v-.008zm0 2.25h.008v.008H15.75v-.008zm0 2.25h.008v.008H15.75v-.008zM3 13.5v-9A2.25 2.25 0 015.25 2.25h13.5A2.25 2.25 0 0121 4.5v9m-18 0v9a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 22.5v-9m-18 0h18" /></svg>
                        </div>
                        <div class="text-center">
                            <h3 class="text-xl font-extrabold text-gray-900 dark:text-white" id="modal-title">Calificar Estudiante</h3>
                            <p class="text-sm font-bold text-blue-600 dark:text-blue-400 mt-1" x-text="studentName"></p>
                        </div>
                    </div>
                    
                    <div class="p-6 sm:p-8 overflow-y-auto custom-scrollbar">
                        @if($course->modules->count() > 0)
                            <div class="space-y-4 mb-6">
                                <h4 class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest border-b border-gray-200 dark:border-slate-700 pb-2">Notas por Módulo (0 - 20)</h4>
                                
                                @foreach($course->modules as $module)
                                    <div class="flex items-center justify-between gap-4 bg-gray-50 dark:bg-slate-800/50 p-3 rounded-xl border border-gray-100 dark:border-slate-700">
                                        <label class="text-sm font-bold text-gray-700 dark:text-slate-300 flex-1 line-clamp-2">
                                            <span class="text-blue-600 dark:text-blue-400 mr-1">M{{ $loop->iteration }}.</span> {{ $module->title }}
                                        </label>
                                        <input type="number" name="module_grades[{{ $module->id }}]" x-model="moduleGrades['{{ $module->id }}']" min="0" max="20" step="0.1" placeholder="-"
                                               class="w-20 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-slate-600 rounded-lg px-2 py-2 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all font-black text-center shadow-inner shrink-0">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nota Única (0 - 20)</label>
                                <input type="number" name="module_grades[final]" x-model="moduleGrades['final']" min="0" max="20" step="0.1"
                                       class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all font-black text-2xl text-center text-blue-800 dark:text-blue-400 placeholder-gray-300 shadow-inner">
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-slate-700 border-dashed">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800/50 flex flex-col justify-center text-center">
                                <label class="block text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-1">Promedio Final</label>
                                <div class="text-3xl font-black text-blue-800 dark:text-blue-300" x-text="averageGrade !== '' ? averageGrade : '-'"></div>
                                <input type="hidden" name="final_grade" :value="averageGrade">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Estado Final</label>
                                <div class="relative">
                                    <select name="status" x-model="status" required
                                            class="w-full appearance-none bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-4 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer font-bold shadow-inner h-full">
                                        <option value="in_progress">Cursando</option>
                                        <option value="approved">Aprobado</option>
                                        <option value="failed">Reprobado</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 dark:bg-[#0f172a]/50 border-t border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row-reverse gap-3 shrink-0">
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-white bg-blue-800 hover:bg-blue-900 dark:bg-blue-600 dark:hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-800/30 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19.25V5.5A2.5 2.5 0 0015 3H6a2.5 2.5 0 00-2.5 2.5v13.5A2.5 2.5 0 006 21h9.5a2.5 2.5 0 002.5-2.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25H9m6 3H9m3 3H9" /></svg>
                            Guardar Calificación
                        </button>
                        <button type="button" @click="showModal = false" class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm text-center">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
