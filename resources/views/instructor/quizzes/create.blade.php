<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nueva Evaluación') }} - {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="quizBuilder()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('instructor.courses.quizzes.store', $course->id) }}" method="POST">
                @csrf
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2 text-red-600">Configuración General</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Título de la Evaluación</label>
                            <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tiempo Límite (Minutos)</label>
                            <input type="number" name="time_limit" value="30" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Descripción / Instrucciones</label>
                            <textarea name="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nota Mínima para Aprobar</label>
                            <input type="number" step="0.01" name="passing_score" value="10.00" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Intentos Permitidos</label>
                            <input type="number" name="max_attempts" value="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6 border-b pb-2">
                        <h3 class="text-lg font-bold text-red-600">Banco de Preguntas</h3>
                        <button type="button" @click="addQuestion()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm transition">
                            + Añadir Pregunta
                        </button>
                    </div>

                    <div class="space-y-8">
                        <template x-for="(question, qIndex) in questions" :key="qIndex">
                            <div class="p-5 border border-gray-200 rounded-xl bg-gray-50 relative">
                                <button type="button" @click="removeQuestion(qIndex)" class="absolute top-4 right-4 text-gray-400 hover:text-red-600">
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                    <div class="md:col-span-3">
                                        <label class="block text-xs font-bold uppercase text-gray-500">Enunciado de la Pregunta</label>
                                        <input type="text" :name="`questions[${qIndex}][text]`" x-model="question.text" required 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                               placeholder="Ej: ¿Cuál es la unidad de medida de la resistencia eléctrica?">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-500">Puntos</label>
                                        <input type="number" :name="`questions[${qIndex}][points]`" x-model="question.points" required 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    </div>
                                </div>

                                <div class="ml-6 space-y-3">
                                    <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Opciones de Respuesta</label>
                                    <template x-for="(option, oIndex) in question.options" :key="oIndex">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" :name="`questions[${qIndex}][correct_index]`" :value="oIndex" required
                                                   class="text-red-600 focus:ring-red-500 h-4 w-4">
                                            
                                            <input type="text" :name="`questions[${qIndex}][options][${oIndex}][text]`" x-model="option.text" required
                                                   class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-red-500 focus:ring-red-500"
                                                   placeholder="Escribe una opción...">

                                            <button type="button" @click="removeOption(qIndex, oIndex)" class="text-gray-300 hover:text-red-400">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </template>
                                    <button type="button" @click="addOption(qIndex)" class="text-xs font-bold text-blue-600 hover:underline">
                                        + Añadir Opción
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-10 border-t pt-6 flex justify-end">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-10 rounded-lg shadow-lg transition duration-300">
                            Guardar y Publicar Evaluación
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function quizBuilder() {
            return {
                questions: [
                    { 
                        text: '', 
                        points: 1, 
                        options: [{ text: '' }, { text: '' }] 
                    }
                ],
                addQuestion() {
                    this.questions.push({ 
                        text: '', 
                        points: 1, 
                        options: [{ text: '' }, { text: '' }] 
                    });
                },
                removeQuestion(index) {
                    if(this.questions.length > 1) {
                        this.questions.splice(index, 1);
                    }
                },
                addOption(qIndex) {
                    this.questions[qIndex].options.push({ text: '' });
                },
                removeOption(qIndex, oIndex) {
                    if(this.questions[qIndex].options.length > 2) {
                        this.questions[qIndex].options.splice(oIndex, 1);
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>

