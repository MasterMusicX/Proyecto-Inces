@extends('layouts.app')

@section('title', 'Mis Entregables y Justificativos')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-3xl p-8 text-white shadow-xl border border-blue-800/50 relative overflow-hidden">
        <div class="absolute inset-0 bg-white/5 opacity-20" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <span class="px-3 py-1 bg-red-600/30 text-red-300 border border-red-500/40 text-xs font-black uppercase tracking-widest rounded-lg mb-3 inline-block">
                    Gestión de Documentos PDF
                </span>
                <h1 class="text-3xl font-black tracking-tight text-white">Tareas y Justificativos Médicos</h1>
                <p class="text-blue-200 text-sm mt-2 max-w-2xl font-medium">
                    Sube tus tareas realizadas en formato PDF o adjunta tus récipes y justificativos médicos para el control y evaluación de tus profesores en el INCES.
                </p>
            </div>
            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-white shrink-0 border border-white/20 shadow-inner">
                <svg class="w-8 h-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Formulario para Subir Documento --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-slate-700/50 sticky top-24 space-y-6">
                <div class="flex items-center gap-3 border-b border-gray-100 dark:border-slate-700 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-gray-900 dark:text-white">Subir Nuevo Documento</h2>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Únicamente archivos en formato PDF</p>
                    </div>
                </div>

                <form action="{{ route('student.submissions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    {{-- Tipo de Documento --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Tipo de Documento <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-red-500 focus:outline-none transition-all font-medium">
                            <option value="assignment">📝 Tarea / Entregable Realizado</option>
                            <option value="medical_receipt">🩺 Récipe / Justificativo Médico</option>
                            <option value="other">📄 Otro Documento PDF</option>
                        </select>
                    </div>

                    {{-- Curso Asignado (Opcional) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Curso o Formación Relacionada
                        </label>
                        <select name="course_id" class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-red-500 focus:outline-none transition-all font-medium">
                            <option value="">-- General / Ningún curso específico --</option>
                            @foreach($enrolledCourses as $c)
                                <option value="{{ $c->id }}">{{ $c->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Título del Entregable --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Título o Descripción Corta <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" required placeholder="Ej: Tarea Módulo 1 - Algoritmos o Récipe Médico 25/07"
                               class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-red-500 focus:outline-none transition-all font-medium">
                    </div>

                    {{-- Observaciones / Comentarios --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Notas u Observaciones (Opcional)
                        </label>
                        <textarea name="notes" rows="3" placeholder="Añade algún comentario explicativo para el profesor..."
                                  class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-800 dark:text-slate-200 focus:ring-2 focus:ring-red-500 focus:outline-none transition-all font-medium resize-none"></textarea>
                    </div>

                    {{-- Input de Archivo PDF --}}
                    <div x-data="{ fileName: '' }">
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Archivo PDF <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-slate-700 hover:border-red-500 dark:hover:border-red-500 rounded-2xl p-4 text-center transition-all cursor-pointer relative bg-gray-50/50 dark:bg-slate-900/50">
                            <input type="file" name="file" accept=".pdf,application/pdf" required 
                                   @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <template x-if="!fileName">
                                <div class="space-y-2">
                                    <svg class="w-10 h-10 mx-auto text-red-500 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                    <p class="text-xs font-bold text-gray-700 dark:text-slate-300">Haz clic o arrastra tu archivo PDF aquí</p>
                                    <p class="text-[11px] text-gray-400">Máximo 10 MB (Solo .pdf)</p>
                                </div>
                            </template>

                            <template x-if="fileName">
                                <div class="flex items-center justify-center gap-2 text-red-600 dark:text-red-400 font-bold text-xs py-2">
                                    <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    <span x-text="fileName" class="truncate max-w-[200px]"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white font-black rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                        <span>Enviar Documento PDF</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Historial de Documentos Subidos --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-slate-700/50">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-slate-700">
                    <div>
                        <h2 class="text-xl font-black text-gray-900 dark:text-white">Historial de Entregables</h2>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Total de archivos subidos: {{ $submissions->total() }}</p>
                    </div>
                </div>

                @forelse($submissions as $sub)
                    <div class="mb-4 bg-gray-50/50 dark:bg-slate-900/40 rounded-2xl p-5 border border-gray-200/80 dark:border-slate-800 transition-all hover:border-blue-500/40">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            
                            {{-- Info --}}
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl shrink-0 flex items-center justify-center shadow-inner {{ $sub->type === 'medical_receipt' ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'bg-blue-500/10 text-blue-500 border border-blue-500/20' }}">
                                    @if($sub->type === 'medical_receipt')
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5-3H12M8.25 9h.008v.008H8.25V9Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                    @endif
                                </div>

                                <div class="space-y-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-xs font-black uppercase tracking-wider px-2.5 py-0.5 rounded-md {{ $sub->type === 'medical_receipt' ? 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' }}">
                                            {{ $sub->type_label }}
                                        </span>
                                        
                                        @if($sub->course)
                                            <span class="text-xs font-bold text-gray-500 dark:text-slate-400">
                                                • {{ $sub->course->title }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-base font-black text-gray-900 dark:text-white leading-snug">
                                        {{ $sub->title }}
                                    </h3>

                                    <p class="text-xs text-gray-500 dark:text-slate-400 flex items-center gap-3">
                                        <span>Subido el: {{ $sub->created_at->format('d/m/Y h:i A') }}</span>
                                        <span>•</span>
                                        <span>PDF ({{ $sub->file_size_human }})</span>
                                    </p>

                                    @if($sub->notes)
                                        <p class="text-xs italic text-gray-600 dark:text-slate-300 bg-white dark:bg-slate-800 p-2.5 rounded-xl border border-gray-100 dark:border-slate-700 mt-2">
                                            "{{ $sub->notes }}"
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Estado y Acciones --}}
                            <div class="flex flex-col sm:items-end justify-between gap-3 shrink-0">
                                {{-- Status Badge --}}
                                @if($sub->status === 'approved')
                                    <span class="px-3 py-1 bg-green-500/20 text-green-700 dark:text-green-300 text-xs font-black rounded-lg border border-green-500/30 flex items-center gap-1.5 self-start sm:self-auto">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span> Aprobado
                                    </span>
                                @elseif($sub->status === 'rejected')
                                    <span class="px-3 py-1 bg-red-500/20 text-red-700 dark:text-red-300 text-xs font-black rounded-lg border border-red-500/30 flex items-center gap-1.5 self-start sm:self-auto">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span> Rechazado
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-amber-500/20 text-amber-700 dark:text-amber-300 text-xs font-black rounded-lg border border-amber-500/30 flex items-center gap-1.5 self-start sm:self-auto">
                                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Pendiente de Revisión
                                    </span>
                                @endif

                                <div class="flex items-center gap-2">
                                    {{-- Botón Abrir PDF --}}
                                    <a href="{{ route('student.submissions.file', $sub) }}" target="_blank"
                                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs flex items-center gap-1.5 transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                        <span>Ver PDF</span>
                                    </a>

                                    {{-- Botón Eliminar si está pendiente --}}
                                    @if($sub->status === 'pending')
                                        <form action="{{ route('student.submissions.destroy', $sub) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este documento enviado?');" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 dark:bg-red-500/10 text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Comentario / Retroalimentación del Profesor --}}
                        @if($sub->feedback)
                            <div class="mt-4 pt-3 border-t border-gray-200 dark:border-slate-800 text-xs">
                                <span class="font-extrabold text-blue-900 dark:text-blue-300 uppercase tracking-wider block mb-1">
                                    💬 Retroalimentación del Profesor:
                                </span>
                                <p class="text-gray-700 dark:text-slate-300 font-medium">
                                    {{ $sub->feedback }}
                                </p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto text-gray-400 mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                        </div>
                        <h3 class="text-base font-extrabold text-gray-800 dark:text-white">Aún no has subido ningún documento</h3>
                        <p class="text-xs text-gray-500 dark:text-slate-400 max-w-sm mx-auto mt-1">Usa el formulario lateral para adjuntar tu primera tarea o récipe médico en formato PDF.</p>
                    </div>
                @endforelse

                <div class="mt-6">
                    {{ $submissions->links() }}
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
