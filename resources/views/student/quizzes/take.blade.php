@extends('layouts.app')

@section('title', 'Presentando Evaluación: ' . $quiz->title)

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    {{-- Header Banner con advertencia de seguridad --}}
    <div class="bg-gradient-to-r from-blue-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-blue-800 relative overflow-hidden">
        <div class="absolute inset-0 bg-white/5 opacity-20" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 bg-red-600/30 text-red-300 border border-red-500/40 text-xs font-black uppercase tracking-widest rounded-lg inline-block">
                        Evaluación Oficial INCES
                    </span>
                    <span class="px-3 py-1 bg-blue-500/20 text-blue-300 border border-blue-400/30 text-xs font-bold rounded-lg flex items-center gap-1.5">
                        ⏱️ Tiempo Estimado: {{ $quiz->time_limit ?? 30 }} min
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">{{ $quiz->title }}</h1>
                <p class="text-blue-200 text-xs sm:text-sm mt-1 font-medium">
                    Curso: <strong>{{ $course->title }}</strong>
                </p>
            </div>
            
            <div class="w-full md:w-auto bg-amber-500/10 border border-amber-500/30 rounded-2xl p-4 text-amber-200 text-xs flex items-center gap-3">
                <svg class="w-6 h-6 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                <p>
                    <strong>Seguridad Activa:</strong> Mantén esta ventana abierta y permite el uso de la cámara. Cambiar de pestaña registrará una alerta de conducta.
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {{-- Preguntas del Examen --}}
        <div class="lg:col-span-3 space-y-6">
            <form action="{{ route('student.quizzes.submit', [$course, $quiz]) }}" method="POST" id="quiz-form" class="space-y-6">
                @csrf
                
                <input type="hidden" name="proctoring_image" id="proctoring_image">
                <input type="hidden" name="suspicious_behavior" id="suspicious_behavior" value="0">

                @forelse($quiz->questions as $index => $question)
                    <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-slate-700/50 space-y-4">
                        <div class="flex items-start justify-between gap-4 border-b border-gray-100 dark:border-slate-700 pb-4">
                            <h3 class="text-base font-black text-gray-900 dark:text-white leading-snug">
                                <span class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-slate-800 text-blue-700 dark:text-blue-400 inline-flex items-center justify-center font-black text-sm mr-2 shadow-sm border border-blue-100 dark:border-slate-700">
                                    {{ $index + 1 }}
                                </span>
                                {{ $question->question_text }}
                            </h3>
                            <span class="text-xs font-black px-3 py-1 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 rounded-lg shrink-0">
                                {{ $question->points ?? 1 }} Pts
                            </span>
                        </div>

                        <div class="space-y-3 pt-2">
                            @foreach($question->options as $option)
                                <label class="flex items-center p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/40 hover:border-red-500/50 dark:hover:border-red-500/50 cursor-pointer transition-all has-[:checked]:border-red-600 has-[:checked]:bg-red-500/5 dark:has-[:checked]:bg-red-500/10">
                                    <input type="radio" 
                                           name="answers[{{ $question->id }}]" 
                                           value="{{ $option->id }}" 
                                           required
                                           class="w-5 h-5 text-red-600 focus:ring-red-500 border-gray-300 dark:border-slate-700">
                                    <span class="ml-3 text-sm font-bold text-gray-800 dark:text-slate-200">
                                        {{ $option->option_text }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-12 text-center border border-gray-100 dark:border-slate-700">
                        <p class="text-gray-500 dark:text-slate-400 font-bold">No hay preguntas disponibles en este examen.</p>
                    </div>
                @endforelse

                @if($quiz->questions->count() > 0)
                    <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">
                            Asegúrate de responder todas las preguntas antes de confirmar el envío.
                        </p>
                        <button type="button" onclick="captureAndSubmit()" 
                                class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white font-black rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                            <span>Finalizar y Enviar Evaluación</span>
                        </button>
                    </div>
                @endif
            </form>
        </div>

        {{-- Barra Lateral: Monitoreo por Cámara --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 sticky top-24 space-y-4">
                <div class="flex items-center gap-2 border-b border-gray-100 dark:border-slate-700 pb-3">
                    <span class="w-3 h-3 rounded-full bg-red-500 animate-ping"></span>
                    <h3 class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider">Cámara de Validación</h3>
                </div>

                <div class="relative w-full aspect-video bg-black rounded-2xl overflow-hidden shadow-inner border border-gray-800">
                    <video id="webcam" autoplay playsinline class="w-full h-full object-cover transform scale-x-[-1]"></video>
                </div>
                
                <p id="cam-status" class="text-xs font-bold text-gray-500 flex items-center gap-2 justify-center bg-gray-50 dark:bg-slate-900 p-2.5 rounded-xl">
                    <svg class="animate-spin h-4 w-4 text-gray-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Iniciando cámara...</span>
                </p>

                <canvas id="snapshot" class="hidden"></canvas>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('webcam');
        const statusText = document.getElementById('cam-status');
        const suspiciousInput = document.getElementById('suspicious_behavior');
        let streamActivo = null;

        // 1. Iniciar Cámara de Seguridad
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    streamActivo = stream;
                    video.srcObject = stream;
                    statusText.innerHTML = "🟢 Cámara Activa y Vigilando";
                    statusText.className = "text-xs font-bold text-green-600 dark:text-green-400 flex items-center gap-2 justify-center bg-green-50 dark:bg-green-500/10 p-2.5 rounded-xl border border-green-500/20";
                })
                .catch(err => {
                    console.error("Error de cámara:", err);
                    statusText.innerHTML = "🔴 Error: Cámara requerida";
                    statusText.className = "text-xs font-bold text-red-600 dark:text-red-400 flex items-center gap-2 justify-center bg-red-50 dark:bg-red-500/10 p-2.5 rounded-xl border border-red-500/20";
                });
        }

        // 2. Detector de Cambio de Pestaña (Anti-trampa)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                suspiciousInput.value = "1";
                console.warn("Actividad sospechosa registrada por cambio de ventana.");
            }
        });
    });

    // 3. Captura de Foto e Inicio de Envío
    function captureAndSubmit() {
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('snapshot');
        const proctoringInput = document.getElementById('proctoring_image');
        const form = document.getElementById('quiz-form');

        // Validar que se hayan respondido todas las preguntas requeridas
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Si la cámara está activa, capturar fotograma
        if (video && video.srcObject && video.videoWidth > 0) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            proctoringInput.value = canvas.toDataURL('image/jpeg', 0.7);

            // Apagar stream de cámara
            const tracks = video.srcObject.getTracks();
            tracks.forEach(track => track.stop());
        } else {
            // Imagen placeholder si no hay cámara activa
            proctoringInput.value = "data:image/jpeg;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==";
        }

        // Enviar formulario al backend
        form.submit();
    }
</script>
@endpush
@endsection