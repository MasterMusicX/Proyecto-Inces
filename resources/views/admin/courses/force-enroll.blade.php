@extends('layouts.app')
@section('title', 'Inscripción Especial de Participantes')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 animate-fade-in-up">
    
    {{-- Encabezado --}}
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 dark:text-white flex items-center gap-2">
            <span>🛡️</span> Inscripción Especial / Forzada
        </h1>
        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
            Esta herramienta permite matricular alumnos omitiendo de forma absoluta las restricciones académicas de prelaciones o unidades curriculares pendientes.
        </p>
    </div>

    {{-- Mensaje de Éxito --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-500/10 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-2xl shadow-sm animate-fade-in-up">
            <div class="flex">
                <span class="text-emerald-500 text-lg">✅</span>
                <p class="ml-3 text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Mensaje de Error de Lógica --}}
    @if(session('error'))
        <div class="bg-rose-50 dark:bg-rose-500/10 border-l-4 border-rose-500 p-4 mb-6 rounded-r-2xl shadow-sm animate-fade-in-up">
            <div class="flex">
                <span class="text-rose-500 text-lg">⚠️</span>
                <p class="ml-3 text-sm font-bold text-rose-800 dark:text-rose-400">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Tarjeta del Formulario --}}
    <div class="bg-white dark:bg-[#1e293b] rounded-[2rem] shadow-xl border border-gray-100 dark:border-slate-700/50 overflow-hidden">
        <div class="p-6 bg-gray-50/50 dark:bg-[#0f172a]/50 border-b border-gray-100 dark:border-slate-700/50 px-8 py-5">
            <h3 class="text-sm font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider">Formulario de Asignación Expresa</h3>
        </div>

        <form method="POST" action="{{ route('admin.courses.force-enroll.post') }}" class="p-8 space-y-6">
            @csrf

            {{-- Buscador por Email --}}
            <div>
                <label for="email" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                    Correo Electrónico del Estudiante
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                           placeholder="ejemplo@estudiante.com"
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border @error('email') border-rose-500 focus:ring-rose-500 @else border-gray-200 dark:border-slate-600 focus:ring-blue-500 @enderror rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:border-transparent outline-none transition-all">
                </div>
                @error('email')
                    <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Selección de Curso --}}
            <div>
                <label for="course_id" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">
                    Seleccionar Unidad Curricular / Curso
                </label>
                <select name="course_id" id="course_id" required
                        class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all cursor-pointer">
                    <option value="" disabled selected>-- Seleccione el curso destino --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }} @if($course->prerequisite_id) (Requiere Prelación) @endif
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Advertencia de Seguridad --}}
            <div class="rounded-xl bg-amber-50 dark:bg-amber-500/5 border border-amber-200 dark:border-amber-500/20 p-4 text-xs font-medium text-amber-800 dark:text-amber-400 leading-relaxed">
                <strong>Nota institucional:</strong> Esta acción quedará grabada en las bitácoras del sistema. Asegúrese de contar con el aval físico o la autorización de la jefatura de centro antes de forzar la carga académica en el historial del participante.
            </div>

            {{-- Botones de Control --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-700/50">
                <a href="{{ route('admin.dashboard') }}" class="px-5 py-3 text-sm font-bold text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5">
                    Ejecutar Inscripción Forzada
                </button>
            </div>
        </form>
    </div>

</div>
@endsection