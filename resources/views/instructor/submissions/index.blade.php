@extends('layouts.app')

@section('title', 'Revisiones de Tareas y Matriz de Notas INCES')

@section('content')
<div class="max-w-7xl mx-auto space-y-8" x-data="{ openReviewModal: false, activeSub: null }">
    
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-3xl p-8 text-white shadow-xl border border-blue-800/50 relative overflow-hidden">
        <div class="absolute inset-0 bg-white/5 opacity-20" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <span class="px-3 py-1 bg-red-600/30 text-red-300 border border-red-500/40 text-xs font-black uppercase tracking-widest rounded-lg mb-3 inline-block">
                    Panel del Instructor • Evaluación Modular
                </span>
                <h1 class="text-3xl font-black tracking-tight text-white">Revisión de Tareas, Calificaciones y Matriz INCES</h1>
                <p class="text-blue-200 text-sm mt-2 max-w-2xl font-medium">
                    Califica las tareas de tus alumnos asignadas por módulos, registra su nota cuantitativa (0-20 pts) y evalúa su <strong>Matriz Vocacional de Habilidades Técnicas INCES</strong>.
                </p>
            </div>
            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-white shrink-0 border border-white/20 shadow-inner">
                <svg class="w-8 h-8 text-blue-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" /></svg>
            </div>
        </div>
    </div>

    {{-- Filtros de Búsqueda --}}
    <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50">
        <form method="GET" action="{{ route('instructor.submissions.index') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-4">
            
            {{-- Filtro por Curso --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Filtrar por Curso</label>
                <select name="course_id" onchange="this.form.submit()" class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-gray-800 dark:text-slate-200 font-bold focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Todos mis cursos</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro por Módulo --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Filtrar por Módulo</label>
                <select name="module_id" onchange="this.form.submit()" class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-gray-800 dark:text-slate-200 font-bold focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Todos los módulos</option>
                    @foreach($courses as $c)
                        @foreach($c->modules as $m)
                            <option value="{{ $m->id }}" {{ request('module_id') == $m->id ? 'selected' : '' }}>{{ $c->title }} &raquo; {{ $m->title }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            {{-- Filtro por Tipo --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Tipo de Documento</label>
                <select name="type" onchange="this.form.submit()" class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-gray-800 dark:text-slate-200 font-bold focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Todos los tipos</option>
                    <option value="assignment" {{ request('type') == 'assignment' ? 'selected' : '' }}>Tareas Realizadas</option>
                    <option value="medical_receipt" {{ request('type') == 'medical_receipt' ? 'selected' : '' }}>Récipes / Justificativos Médicos</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Otros Documentos</option>
                </select>
            </div>

            {{-- Filtro por Estado --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Estado</label>
                <select name="status" onchange="this.form.submit()" class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-gray-800 dark:text-slate-200 font-bold focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendientes de Revisión</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprobados</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazados</option>
                </select>
            </div>

            {{-- Reset --}}
            <div class="flex items-end">
                <a href="{{ route('instructor.submissions.index') }}" class="w-full py-2 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold rounded-xl text-xs flex items-center justify-center gap-2 transition-colors">
                    Limpiar Filtros
                </a>
            </div>
        </form>
    </div>

    {{-- Tabla de Entregables --}}
    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
            <h2 class="text-xl font-black text-gray-900 dark:text-white">Entregables y Evaluaciones Modulares</h2>
            <span class="text-xs text-gray-500 dark:text-slate-400 font-bold">Total: {{ $submissions->total() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-800 text-[11px] font-black uppercase tracking-wider text-gray-500 dark:text-slate-400">
                        <th class="py-4 px-6">Estudiante</th>
                        <th class="py-4 px-6">Tarea / Módulo</th>
                        <th class="py-4 px-6">Curso</th>
                        <th class="py-4 px-6 text-center">Nota Asignada</th>
                        <th class="py-4 px-6 text-center">Estado</th>
                        <th class="py-4 px-6 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($submissions as $sub)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            
                            {{-- Estudiante --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $sub->user->avatar_url }}" alt="{{ $sub->user->name }}" class="w-9 h-9 rounded-xl object-cover border border-gray-200 dark:border-slate-700">
                                    <div>
                                        <div class="font-extrabold text-gray-900 dark:text-white">{{ $sub->user->name }} {{ $sub->user->last_name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-slate-400">C.I: {{ $sub->user->cedula ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Documento y Módulo --}}
                            <td class="py-4 px-6">
                                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded {{ $sub->type === 'medical_receipt' ? 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' }}">
                                    {{ $sub->type_label }}
                                </span>
                                <div class="font-bold text-gray-900 dark:text-white mt-1">{{ $sub->title }}</div>
                                
                                @if($sub->module)
                                    <div class="text-xs font-bold text-indigo-600 dark:text-indigo-400 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-.84-1.875-1.875-1.875s-1.875.84-1.875 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0c0 .355-.186.676-.401.959-.221.29-.349.634-.349 1.003 0 1.036.84 1.875 1.875 1.875s1.875-.84 1.875-1.875c0-.369-.128-.713-.349-1.003a2.25 2.25 0 0 1-.401-.959v0Z" /></svg>
                                        <span>Módulo: {{ $sub->module->title }}</span>
                                    </div>
                                @endif

                                @if($sub->notes)
                                    <div class="text-xs text-gray-500 dark:text-slate-400 italic truncate max-w-xs" title="{{ $sub->notes }}">
                                        "{{ $sub->notes }}"
                                    </div>
                                @endif
                            </td>

                            {{-- Curso --}}
                            <td class="py-4 px-6 font-medium text-xs text-gray-700 dark:text-slate-300">
                                {{ $sub->course->title ?? 'General' }}
                            </td>

                            {{-- Nota --}}
                            <td class="py-4 px-6 text-center">
                                @if($sub->grade !== null)
                                    <span class="px-3 py-1.5 bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-xs font-black rounded-xl border border-emerald-500/30 inline-block shadow-sm">
                                        {{ number_format($sub->grade, 1) }} / 20 pts
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-gray-400 italic">
                                        Sin calificar
                                    </span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td class="py-4 px-6 text-center">
                                @if($sub->status === 'approved')
                                    <span class="px-3 py-1 bg-green-500/20 text-green-700 dark:text-green-300 text-xs font-black rounded-lg border border-green-500/30 inline-block">
                                        Aprobado
                                    </span>
                                @elseif($sub->status === 'rejected')
                                    <span class="px-3 py-1 bg-red-500/20 text-red-700 dark:text-red-300 text-xs font-black rounded-lg border border-red-500/30 inline-block">
                                        Rechazado
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-amber-500/20 text-amber-700 dark:text-amber-300 text-xs font-black rounded-lg border border-amber-500/30 inline-block animate-pulse">
                                        Pendiente
                                    </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('student.submissions.file', $sub) }}" target="_blank" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs inline-flex items-center gap-1 shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                    Ver PDF
                                </a>

                                <button @click="openReviewModal = true; activeSub = {{ json_encode($sub) }}" class="px-3 py-1.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white font-bold rounded-xl text-xs inline-flex items-center gap-1 shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                    Calificar / Revisar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500 dark:text-slate-400 text-sm">
                                No se encontraron tareas ni entregables con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-slate-800">
            {{ $submissions->links() }}
        </div>
    </div>

    {{-- MODAL DE REVISIÓN, NOTAS Y MATRIZ VOCACIONAL INCES (MODELO ÚNICO) --}}
    <div x-show="openReviewModal" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm overflow-y-auto"
         x-transition.opacity>
        
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl border border-gray-100 dark:border-slate-700 space-y-6 my-8"
             @click.away="openReviewModal = false">
            
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-700 pb-4">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-red-600 dark:text-red-400 block">Evaluación INCES</span>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white">Calificar Tarea & Matriz de Habilidades</h3>
                </div>
                <button @click="openReviewModal = false" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <template x-if="activeSub">
                <form :action="'/instructor/submissions/' + activeSub.id + '/review'" method="POST" class="space-y-6">
                    @csrf

                    {{-- Info Resumen --}}
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 dark:bg-slate-900/60 p-4 rounded-2xl border border-gray-200/80 dark:border-slate-800 text-xs">
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase">Estudiante:</p>
                            <p class="font-black text-gray-900 dark:text-white text-sm" x-text="activeSub.user ? activeSub.user.name + ' ' + activeSub.user.last_name : 'Estudiante'"></p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase">Tarea / Documento:</p>
                            <p class="font-bold text-blue-600 dark:text-blue-400 text-sm" x-text="activeSub.title"></p>
                        </div>
                        <template x-if="activeSub.module">
                            <div class="col-span-2 pt-2 border-t border-gray-200/60 dark:border-slate-800">
                                <p class="text-[10px] text-gray-400 font-black uppercase">Módulo Asignado:</p>
                                <p class="font-bold text-indigo-600 dark:text-indigo-400 text-xs" x-text="activeSub.module.title"></p>
                            </div>
                        </template>
                    </div>

                    {{-- Fila: Dictamen y Nota Cuantitativa --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Selección de Estado --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Dictamen de la Tarea <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="border-2 border-gray-200 dark:border-slate-700 rounded-xl p-3 flex items-center gap-2 cursor-pointer hover:border-green-500 has-[:checked]:border-green-500 has-[:checked]:bg-green-500/10 transition-all">
                                    <input type="radio" name="status" value="approved" required :checked="activeSub.status === 'approved'" class="text-green-600 focus:ring-green-500">
                                    <span class="text-xs font-black text-green-700 dark:text-green-300">APROBAR</span>
                                </label>
                                <label class="border-2 border-gray-200 dark:border-slate-700 rounded-xl p-3 flex items-center gap-2 cursor-pointer hover:border-red-500 has-[:checked]:border-red-500 has-[:checked]:bg-red-500/10 transition-all">
                                    <input type="radio" name="status" value="rejected" required :checked="activeSub.status === 'rejected'" class="text-red-600 focus:ring-red-500">
                                    <span class="text-xs font-black text-red-700 dark:text-red-300">RECHAZAR</span>
                                </label>
                            </div>
                        </div>

                        {{-- Nota Cuantitativa (0 a 20 pts) --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Calificación (Nota 0-20 Pts)
                            </label>
                            <div class="relative">
                                <input type="number" name="grade" step="0.5" min="0" max="20" :value="activeSub.grade" placeholder="Ej: 18.5"
                                       class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-base font-black text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all">
                                <span class="absolute right-4 top-3 text-xs font-bold text-gray-400">/ 20 Pts</span>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN MODELO ÚNICO: MATRIZ DE COMPETENCIAS INCES --}}
                    <div class="bg-gradient-to-br from-slate-900 to-indigo-950 p-5 rounded-2xl border border-indigo-500/30 space-y-4 text-white">
                        <div class="flex items-center justify-between border-b border-indigo-500/30 pb-2">
                            <h4 class="text-xs font-black uppercase tracking-wider text-indigo-300 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 0 3-3V9.75a3 3 0 0 0-3-3h-9a3 3 0 0 0-3 3v6a3 3 0 0 0 3 3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-6.75a1.125 1.125 0 0 0-1.125 1.125V18.75" /></svg>
                                Matriz Vocacional de Habilidades Técnicas (Modelo Único INCES)
                            </h4>
                            <span class="text-[10px] bg-indigo-500/20 text-indigo-200 px-2 py-0.5 rounded border border-indigo-400/30 font-bold">Evaluación Cualitativa</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Destreza Técnica y Ejecución (1-5):</label>
                                <select name="rubric[technical_skill]" class="w-full bg-slate-900 border border-indigo-800/80 rounded-lg px-3 py-1.5 text-amber-400 font-bold focus:ring-1 focus:ring-indigo-400">
                                    <option value="5">5 / 5 - Excelente Dominio</option>
                                    <option value="4" selected>4 / 5 - Muy Bueno</option>
                                    <option value="3">3 / 5 - Aceptable / En Desarrollo</option>
                                    <option value="2">2 / 5 - Necesita Práctica</option>
                                    <option value="1">1 / 5 - Deficiente</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Calidad y Presentación (1-5):</label>
                                <select name="rubric[work_quality]" class="w-full bg-slate-900 border border-indigo-800/80 rounded-lg px-3 py-1.5 text-emerald-400 font-bold focus:ring-1 focus:ring-indigo-400">
                                    <option value="5">5 / 5 - Impecable / Profesional</option>
                                    <option value="4" selected>4 / 5 - Cumple Estándar Superior</option>
                                    <option value="3">3 / 5 - Aceptable</option>
                                    <option value="2">2 / 5 - Requiere Mejoras</option>
                                    <option value="1">1 / 5 - Deficiente</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Normas de Seguridad y Proceso (1-5):</label>
                                <select name="rubric[safety_standards]" class="w-full bg-slate-900 border border-indigo-800/80 rounded-lg px-3 py-1.5 text-blue-400 font-bold focus:ring-1 focus:ring-indigo-400">
                                    <option value="5">5 / 5 - Rigurosa Aplicación</option>
                                    <option value="4" selected>4 / 5 - Cumple Normativa</option>
                                    <option value="3">3 / 5 - Aplicación Parcial</option>
                                    <option value="2">2 / 5 - Fallas Menores</option>
                                    <option value="1">1 / 5 - Omisión de Normas</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-300 mb-1">Innovación y Criterio Técnico (1-5):</label>
                                <select name="rubric[innovation]" class="w-full bg-slate-900 border border-indigo-800/80 rounded-lg px-3 py-1.5 text-purple-400 font-bold focus:ring-1 focus:ring-indigo-400">
                                    <option value="5">5 / 5 - Creativo y Autónomo</option>
                                    <option value="4" selected>4 / 5 - Buen Criterio Práctico</option>
                                    <option value="3">3 / 5 - Convencional</option>
                                    <option value="2">2 / 5 - Guiado Estricto</option>
                                    <option value="1">1 / 5 - Sin iniciativa</option>
                                </select>
                            </div>
                        </div>

                        {{-- Distintivo de Excelencia --}}
                        <div>
                            <label class="block text-xs font-bold text-indigo-200 mb-1">Insignia / Reconocimiento Especial INCES:</label>
                            <select name="rubric[badge]" class="w-full bg-slate-900 border border-indigo-700/80 rounded-xl px-3 py-2 text-indigo-300 font-black text-xs">
                                <option value="Excelencia Técnica INCES">Distintivo de Excelencia Técnica INCES</option>
                                <option value="Cumplimiento del Estándar Industrial" selected>Cumplimiento del Estándar Industrial</option>
                                <option value="Dominio Práctico Destacado">Dominio Práctico Destacado</option>
                                <option value="En Proceso de Dominio">En Proceso de Dominio Práctico</option>
                            </select>
                        </div>
                    </div>

                    {{-- Comentarios de Retroalimentación --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Comentarios o Notas de Retroalimentación para el Alumno
                        </label>
                        <textarea name="feedback" rows="3" x-model="activeSub.feedback" placeholder="Indica observaciones detalladas, recomendaciones o felicita al alumno por su trabajo..."
                                  class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all resize-none font-medium"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-700">
                        <button type="button" @click="openReviewModal = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-xl text-xs">
                            Cancelar
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-500 hover:to-indigo-600 text-white font-black rounded-xl text-xs shadow-lg shadow-blue-600/20">
                            Guardar Calificación y Matriz
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

</div>
@endsection
