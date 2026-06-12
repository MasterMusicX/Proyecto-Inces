@extends('layouts.app')
@section('title', 'Crear Evaluación - ' . $course->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up" x-data="quizBuilder()">
    
    <div class="mb-8">
        {{-- 🔥 CORRECCIÓN: ->id explícito --}}
        <a href="{{ route('instructor.courses.show', $course->id) }}" class="inline-flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-800 dark:hover:text-blue-400 transition-colors mb-3">
            ← Volver al Curso
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            📝 Configurar Evaluación Final
        </h1>
        <p class="text-gray-500 dark:text-slate-400 mt-2">Diseña las preguntas y opciones para el curso: <span class="font-bold text-blue-600 dark:text-blue-400">{{ $course->title }}</span></p>
    </div>

    {{-- 🔥 CORRECCIÓN: Ruta anidada exacta y con ->id 🔥 --}}
    <form action="{{ route('instructor.courses.quizzes.store', $course->id) }}" method="POST" class="space-y-8">
        @csrf

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl shadow-sm">
                <strong class="font-bold text-sm">¡Atención! Revisa los siguientes errores:</strong>
                <ul class="mt-1 list-disc list-inside text-xs font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- TARJETA 1: CONFIGURACIÓN BÁSICA --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 sm:p-8">
            <h2 class="text-lg font-black text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-slate-700 pb-3">1. Parámetros del Examen</h2>
            
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
                    <p class="text-[10px] text-gray-400 mt-1 text-center">Escala del 1 al 20.</p>
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
                <h2 class="text-lg font-black text-gray-900 dark:text-white">2. Preguntas de Selección Múltiple</h2>
                <div class="text-sm font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-4 py-2 rounded-lg">
                    Total Preguntas: <span x-text="questions.length"></span>
                </div>
            </div>

            <div class="space-y-8">
                <template x-for="(question, qIndex) in questions" :key="qIndex">
                    <div class="p-6 bg-gray-50 dark:bg-[#0f172a]/50 border border-gray-200 dark:border-slate-700 rounded-2xl relative group">
                        
                        {{-- Botón Eliminar Pregunta --}}
                        <button type="button" @click="removeQuestion(qIndex)" x-show="questions.length > 1"
                                class="absolute -top-3 -right-3 bg-red-100 text-red-600 hover:bg-red-600 hover:text-white rounded-full p-2 shadow-sm transition-colors" title="Eliminar pregunta">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>

                        <div class="flex flex-col md:flex-row gap-4 mb-4">
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2" x-text="`Pregunta ${qIndex + 1} *`"></label>
                                <input type="text" x-model="question.text" :name="`questions[${qIndex}][text]`" required placeholder="Escribe el enunciado aquí..."
                                       class="w-full bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-600 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all font-medium">
                            </div>
                            <div class="w-full md:w-32 shrink-0">
                                <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2">Puntos *</label>
                                <input type="number" x-model="question.points" :name="`questions[${qIndex}][points]`" required min="1"
                                       class="w-full bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-600 rounded-xl px-4 py-3 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all text-center font-bold">
                            </div>
                        </div>

                        <div class="pl-0 md:pl-6 border-l-2 border-blue-100 dark:border-slate-700/50 space-y-3 mt-6">
                            <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-3">Opciones (Marca la correcta con el círculo)</p>
                            
                            <template x-for="(option, oIndex) in question.options" :key="oIndex">
                                <div class="flex items-center gap-3">
                                    <input type="radio" :name="`questions[${qIndex}][correct_index]`" :value="oIndex" x-model="question.correct_index" required
                                           class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                                    
                                    <input type="text" x-model="option.text" :name="`questions[${qIndex}][options][${oIndex}][text]`" required :placeholder="`Opción ${oIndex + 1}`"
                                           class="flex-1 bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-600 rounded-lg px-4 py-2 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-800 transition-all text-sm"
                                           :class="question.correct_index == oIndex ? 'border-blue-500 dark:border-blue-500 bg-blue-50/50 dark:bg-blue-900/10' : ''">
                                    
                                    <button type="button" @click="removeOption(qIndex, oIndex)" x-show="question.options.length > 2"
                                            class="text-gray-400 hover:text-red-500 transition-colors p-1" title="Eliminar opción">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </template>

                            <button type="button" @click="addOption(qIndex)" x-show="question.options.length < 5"
                                    class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center gap-1 mt-2 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Agregar opción
                            </button>
                        </div>

                    </div>
                </template>
            </div>

            <div class="mt-8 text-center border-t border-dashed border-gray-200 dark:border-slate-700 pt-8">
                <button type="button" @click="addQuestion"
                        class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors border border-gray-200 dark:border-slate-600">
                    <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Añadir Nueva Pregunta
                </button>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4">
            {{-- 🔥 CORRECCIÓN: ->id explícito también aquí --}}
            <a href="{{ route('instructor.courses.show', $course) }}" class="px-6 py-3 font-bold text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-8 py-3.5 bg-red-600 hover:bg-red-700 text-white font-black rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-1 flex items-center gap-2">
                💾 Guardar Evaluación
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