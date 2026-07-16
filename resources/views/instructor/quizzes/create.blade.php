@extends('layouts.app')
@section('title', 'Crear Evaluación - ' . $course->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up" x-data="quizBuilder()">
    
    <div class="mb-8">
        {{-- 🔥 Botón de regreso con SVG animado 🔥 --}}
        <a href="{{ route('instructor.courses.show', $course->id) }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-4 group">
            <svg class="w-4 h-4 mr-1.5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Volver al Curso
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center shadow-inner shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12" /></svg>
            </div>
            Configurar Evaluación Final
        </h1>
        <p class="text-gray-500 dark:text-slate-400 mt-2">Diseña las preguntas y opciones para el curso: <span class="font-bold text-blue-600 dark:text-blue-400">{{ $course->title }}</span></p>
    </div>

    {{-- 🔥 CORRECCIÓN 404: Ruta enviada como arreglo explícito 🔥 --}}
    <form action="{{ route('instructor.courses.quizzes.store', ['course' => $course->id]) }}" method="POST" class="space-y-8">
        @csrf

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl shadow-sm flex gap-3">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <div>
                    <strong class="font-bold text-sm block mb-1">¡Atención! Revisa los siguientes errores:</strong>
                    <ul class="list-disc list-inside text-xs font-medium space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- TARJETA 1: CONFIGURACIÓN BÁSICA --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 sm:p-8">
            <h2 class="text-lg font-black text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-slate-700 pb-3 flex items-center gap-2">
                <span class="text-blue-500">1.</span> Parámetros del Examen
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Título de la Evaluación *</label>
                    <input type="text" name="title" required value="{{ old('title', 'Evaluación Final - ' . $course->title) }}"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Tiempo Límite (Minutos) *</label>
                    <input type="number" name="time_limit" required min="1" value="{{ old('time_limit', 30) }}"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all text-center font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Puntaje para Aprobar *</label>
                    <input type="number" name="passing_score" required min="1" max="20" value="{{ old('passing_score', 10) }}"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all text-center font-bold">
                    <p class="text-[10px] text-gray-400 mt-1.5 text-center font-bold">Escala del 1 al 20.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Intentos Permitidos</label>
                    <input type="number" name="max_attempts" required min="1" value="{{ old('max_attempts', 1) }}"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all text-center font-bold">
                </div>
            </div>
        </div>

        {{-- TARJETA 2: CONSTRUCTOR DE PREGUNTAS (ALPINE JS) --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-100 dark:border-slate-700 pb-4 mb-6 gap-4">
                <h2 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="text-blue-500">2.</span> Preguntas de Selección Múltiple
                </h2>
                <div class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 px-4 py-2 rounded-lg flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                    Total Preguntas: <span x-text="questions.length" class="text-sm"></span>
                </div>
            </div>

            <div class="space-y-8">
                <template x-for="(question, qIndex) in questions" :key="qIndex">
                    <div class="p-6 bg-gray-50 dark:bg-[#0f172a]/50 border border-gray-200 dark:border-slate-700 rounded-2xl relative group shadow-sm transition-all hover:border-blue-200 dark:hover:border-slate-500">
                        
                        {{-- Botón Eliminar Pregunta --}}
                        <button type="button" @click="removeQuestion(qIndex)" x-show="questions.length > 1"
                                class="absolute -top-3 -right-3 bg-white dark:bg-slate-800 text-red-500 hover:bg-red-600 hover:text-white border border-gray-200 dark:border-slate-600 rounded-full p-2 shadow-md transition-colors" title="Eliminar pregunta">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>

                        <div class="flex flex-col md:flex-row gap-4 mb-4">
                            <div class="flex-1">
                                <label class="block text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 text-blue-600 dark:text-blue-400" x-text="`Pregunta ${qIndex + 1} *`"></label>
                                <input type="text" x-model="question.text" :name="`questions[${qIndex}][text]`" required placeholder="Escribe el enunciado de la pregunta aquí..."
                                       class="w-full bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-600 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all font-medium shadow-inner">
                            </div>
                            <div class="w-full md:w-32 shrink-0">
                                <label class="block text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 text-center">Puntos *</label>
                                <input type="number" x-model="question.points" :name="`questions[${qIndex}][points]`" required min="1"
                                       class="w-full bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-600 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all text-center font-black shadow-inner">
                            </div>
                        </div>

                        <div class="pl-0 md:pl-6 border-l-0 md:border-l-2 border-blue-100 dark:border-slate-700/50 space-y-3 mt-6">
                            <p class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                Opciones (Marca el círculo de la correcta)
                            </p>
                            
                            <template x-for="(option, oIndex) in question.options" :key="oIndex">
                                <div class="flex items-center gap-3">
                                    <input type="radio" :name="`questions[${qIndex}][correct_index]`" :value="oIndex" x-model="question.correct_index" required
                                           class="w-5 h-5 text-blue-600 bg-white border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600 cursor-pointer shadow-inner">
                                    
                                    <input type="text" x-model="option.text" :name="`questions[${qIndex}][options][${oIndex}][text]`" required :placeholder="`Opción ${oIndex + 1}`"
                                           class="flex-1 bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-600 rounded-lg px-4 py-2.5 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all text-sm shadow-inner"
                                           :class="question.correct_index == oIndex ? 'border-blue-500 dark:border-blue-500 bg-blue-50/50 dark:bg-blue-900/10 shadow-blue-500/10' : ''">
                                    
                                    <button type="button" @click="removeOption(qIndex, oIndex)" x-show="question.options.length > 2"
                                            class="text-gray-400 hover:text-red-500 transition-colors p-1" title="Eliminar opción">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </template>

                            <button type="button" @click="addOption(qIndex)" x-show="question.options.length < 5"
                                    class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center gap-1 mt-3 transition-colors bg-blue-50 dark:bg-blue-900/20 px-3 py-1.5 rounded-lg w-max border border-blue-100 dark:border-blue-800/50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Agregar opción
                            </button>
                        </div>

                    </div>
                </template>
            </div>

            <div class="mt-8 text-center border-t border-dashed border-gray-200 dark:border-slate-700 pt-8">
                <button type="button" @click="addQuestion"
                        class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-slate-700 transition-all border border-gray-200 dark:border-slate-600 shadow-sm hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Añadir Nueva Pregunta
                </button>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200 dark:border-slate-700/50 mt-8">
            <a href="{{ route('instructor.courses.show', ['course' => $course->id]) }}" class="px-6 py-3 font-bold text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-8 py-3.5 bg-red-600 hover:bg-red-700 text-white font-black rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-1 flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19.25V5.5A2.5 2.5 0 0 0 15 3H6a2.5 2.5 0 0 0-2.5 2.5v13.5A2.5 2.5 0 0 0 6 21h9.5a2.5 2.5 0 0 0 2.5-2.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25H9m6 3H9m3 3H9" /></svg>
                Guardar Evaluación
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('quizBuilder', () => ({
            questions: [
                {
                    text: '',
                    points: 1,
                    correct_index: 0,
                    options: [
                        { text: '' },
                        { text: '' },
                        { text: '' } // 3 opciones por defecto para arrancar
                    ]
                }
            ],
            addQuestion() {
                this.questions.push({
                    text: '',
                    points: 1,
                    correct_index: 0,
                    options: [ { text: '' }, { text: '' } ]
                });
            },
            removeQuestion(index) {
                if (this.questions.length > 1) {
                    this.questions.splice(index, 1);
                }
            },
            addOption(qIndex) {
                if (this.questions[qIndex].options.length < 5) {
                    this.questions[qIndex].options.push({ text: '' });
                }
            },
            removeOption(qIndex, oIndex) {
                if (this.questions[qIndex].options.length > 2) {
                    this.questions[qIndex].options.splice(oIndex, 1);
                    // Si borramos la opción que estaba marcada como correcta, reseteamos a la primera
                    if (this.questions[qIndex].correct_index >= this.questions[qIndex].options.length) {
                        this.questions[qIndex].correct_index = 0;
                    }
                }
            }
        }));
    });
</script>
@endsection
