<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Presentando Evaluación: {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>¡Atención!</strong> Esta evaluación cuenta con monitoreo de seguridad. No minimices la ventana ni cambies de pestaña, de lo contrario tu intento será marcado como sospechoso y revisado por tu MTP.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                
                <div class="w-full md:w-3/4 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form action="{{ route('student.quizzes.submit', [$course, $quiz]) }}" method="POST" id="quiz-form">
                        @csrf
                        
                        <input type="hidden" name="proctoring_image" id="proctoring_image">
                        <input type="hidden" name="suspicious_behavior" id="suspicious_behavior" value="0">

                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $quiz->course->title }}</h3>
                        <p class="text-gray-600 mb-8">{{ $quiz->description }}</p>

                        @foreach($quiz->questions as $index => $question)
                            <div class="mb-8 p-4 border border-gray-200 rounded-lg bg-gray-50">
                                <p class="text-md font-semibold text-gray-800 mb-4">
                                    {{ $index + 1 }}. {{ $question->question_text }}
                                    <span class="text-xs text-gray-500 font-normal ml-2">({{ $question->points }} pts)</span>
                                </p>

                                <div class="space-y-2 pl-4">
                                    @foreach($question->options as $option)
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   value="{{ $option->id }}" 
                                                   class="form-radio h-4 w-4 text-red-600 transition duration-150 ease-in-out" 
                                                   required>
                                            <span class="text-gray-700">{{ $option->option_text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-6 border-t pt-4">
                            <button type="button" onclick="captureAndSubmit()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg w-full md:w-auto shadow-md transition duration-300">
                                Finalizar y Enviar Evaluación
                            </button>
                        </div>
                    </form>
                </div>

                <div class="w-full md:w-1/4">
                    <div class="bg-white p-4 shadow-sm sm:rounded-lg sticky top-6 border-t-4 border-red-600 flex flex-col items-center">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider">Cámara de Seguridad</h3>
                        
                        <div class="relative w-full aspect-video bg-black rounded-lg overflow-hidden shadow-inner">
                            <video id="webcam" autoplay playsinline class="w-full h-full object-cover transform scale-x-[-1]"></video>
                        </div>
                        
                        <p id="cam-status" class="text-xs font-semibold text-gray-500 mt-3 flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Iniciando cámara...
                        </p>

                        <canvas id="snapshot" class="hidden"></canvas>
                    </div>
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

            // 1. Iniciar Cámara
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    streamActivo = stream;
                    video.srcObject = stream;
                    statusText.innerHTML = "🟢 Cámara Activa y Vigilando";
                    statusText.classList.remove('text-gray-500');
                    statusText.classList.add('text-green-600');
                })
                .catch(err => {
                    console.error("Error de cámara:", err);
                    statusText.innerHTML = "🔴 Error: Cámara requerida";
                    statusText.classList.remove('text-gray-500');
                    statusText.classList.add('text-red-600');
                    alert("Por políticas del INCES, debes permitir el acceso a la cámara web para presentar la evaluación.");
                });

            // 2. Detector de Cambio de Pestaña (Anti-trampa)
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    suspiciousInput.value = "1";
                    console.warn("Actividad sospechosa registrada.");
                }
            });
        });

        // 3. Tomar Foto y Enviar
        function captureAndSubmit() {
            const video = document.getElementById('webcam');
            const canvas = document.getElementById('snapshot');
            const proctoringInput = document.getElementById('proctoring_image');
            const form = document.getElementById('quiz-form');

            // Validar que se hayan respondido todas las preguntas
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (!video.srcObject) {
                alert("No se puede enviar la evaluación sin validar tu identidad con la cámara.");
                return;
            }

            // Configurar tamaño del canvas y dibujar el video actual
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Extraer foto en Base64 y guardarla en el input oculto (calidad 70%)
            proctoringInput.value = canvas.toDataURL('image/jpeg', 0.7);

            // Apagar la cámara antes de enviar para no dejar el bombillo prendido
            const tracks = video.srcObject.getTracks();
            tracks.forEach(track => track.stop());

            // Enviar formulario al controlador de Laravel
            form.submit();
        }
    </script>
    @endpush
</x-app-layout>