@extends('layouts.app')
@section('title', 'Inscripción por Prelación y Módulos')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 animate-fade-in-up" x-data="forceEnrollForm()">
    
    {{-- Encabezado --}}
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 dark:text-white flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
            </svg>
            Inscripción Expresa / Prelación y Módulos
        </h1>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-2">
            Permite matricular a un estudiante en un curso completo o módulo específico cuando la formación ya se encuentra en marcha, gestionando las prelaciones de la malla curricular.
        </p>
    </div>

    {{-- Mensajes Flash --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-500/10 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-2xl shadow-sm animate-fade-in-up flex items-center gap-3">
            <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 dark:bg-rose-500/10 border-l-4 border-rose-500 p-4 mb-6 rounded-r-2xl shadow-sm animate-fade-in-up flex items-center gap-3">
            <svg class="w-6 h-6 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
            <p class="text-sm font-bold text-rose-800 dark:text-rose-400">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Tarjeta del Formulario --}}
    <div class="bg-white dark:bg-[#1e293b] rounded-[2rem] shadow-xl border border-gray-100 dark:border-slate-700/50 overflow-hidden">
        <div class="p-6 bg-gray-50/50 dark:bg-[#0f172a]/50 border-b border-gray-100 dark:border-slate-700/50 px-8 py-5">
            <h3 class="text-xs font-black text-gray-700 dark:text-slate-300 uppercase tracking-widest">Asignación Directa por Prelación y Módulos</h3>
        </div>

        <form method="POST" action="{{ route('admin.courses.force-enroll.post') }}" class="p-8 space-y-6">
            @csrf

            {{-- Selección de Estudiante --}}
            <div>
                <label for="email" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                    Correo Electrónico del Estudiante *
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <input type="email" name="email" id="email" required list="students_list" value="{{ old('email') }}"
                           placeholder="Ingresa o selecciona el correo del estudiante"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border @error('email') border-rose-500 focus:ring-rose-500 @else border-gray-200 dark:border-slate-600 focus:ring-blue-500 @enderror rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:border-transparent outline-none transition-all">
                    <datalist id="students_list">
                        @foreach($students as $st)
                            <option value="{{ $st->email }}">{{ $st->name }} {{ $st->last_name }} (C.I: {{ $st->cedula ?? 'N/A' }})</option>
                        @endforeach
                    </datalist>
                </div>
                @error('email')
                    <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Selección de Curso --}}
            <div>
                <label for="course_id" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                    Curso / Formación Destino *
                </label>
                <select name="course_id" id="course_id" required x-model="selectedCourseId" @change="onCourseChange()"
                        class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer">
                    <option value="" disabled selected>-- Seleccione el curso destino --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }} @if($course->prerequisite) (Prelación: {{ $course->prerequisite->title }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tipo de Inscripción --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                    Tipo de Inscripción *
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="border-2 border-gray-200 dark:border-slate-700 rounded-2xl p-4 flex items-start gap-3 cursor-pointer hover:border-blue-500 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 dark:has-[:checked]:bg-blue-900/20 transition-all">
                        <input type="radio" name="enrollment_type" value="full" x-model="enrollmentType" class="mt-1 text-blue-600 focus:ring-blue-500">
                        <div>
                            <span class="font-bold text-gray-900 dark:text-white text-sm block">Curso Completo</span>
                            <span class="text-xs text-gray-500 dark:text-slate-400 block mt-0.5">Inscribe al estudiante en todos los módulos de la malla académica.</span>
                        </div>
                    </label>

                    <label class="border-2 border-gray-200 dark:border-slate-700 rounded-2xl p-4 flex items-start gap-3 cursor-pointer hover:border-blue-500 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 dark:has-[:checked]:bg-blue-900/20 transition-all">
                        <input type="radio" name="enrollment_type" value="module" x-model="enrollmentType" class="mt-1 text-blue-600 focus:ring-blue-500">
                        <div>
                            <span class="font-bold text-gray-900 dark:text-white text-sm block">Módulo Específico</span>
                            <span class="text-xs text-gray-500 dark:text-slate-400 block mt-0.5">Permite cursar un módulo determinado en una formación ya iniciada.</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Selección de Módulo Específico (si corresponde) --}}
            <div x-show="enrollmentType === 'module'" style="display: none;" x-transition>
                <label for="module_id" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                    Módulo Específico a Inscribir *
                </label>
                <select name="module_id" id="module_id" :required="enrollmentType === 'module'"
                        class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer">
                    <option value="" disabled selected>-- Selecciona el módulo específico --</option>
                    <template x-for="mod in availableModules" :key="mod.id">
                        <option :value="mod.id" x-text="'Módulo ' + mod.sort_order + ': ' + mod.title"></option>
                    </template>
                </select>
                @error('module_id')
                    <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Advertencia Institucional --}}
            <div class="rounded-2xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 p-4 text-xs font-medium text-amber-800 dark:text-amber-300 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
                <div class="leading-relaxed">
                    <strong>Nota institucional:</strong> Esta inscripción por prelación omitirá restricciones previas y registrará la incorporación del estudiante en la cohorte seleccionada.
                </div>
            </div>

            {{-- Botones de Control --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-700/50">
                <a href="{{ route('admin.dashboard') }}" class="px-5 py-3 text-sm font-bold text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    <span>Ejecutar Inscripción Expresa</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
function forceEnrollForm() {
    return {
        selectedCourseId: '{{ old('course_id') }}',
        enrollmentType: '{{ old('enrollment_type', 'full') }}',
        coursesData: @json($courses),
        availableModules: [],
        init() {
            if (this.selectedCourseId) {
                this.onCourseChange();
            }
        },
        onCourseChange() {
            const course = this.coursesData.find(c => c.id == this.selectedCourseId);
            this.availableModules = course && course.modules ? course.modules : [];
        }
    }
}
</script>
@endsection