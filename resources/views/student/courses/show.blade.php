@extends('layouts.app')
@section('title', $course->title)

@section('content')
{{-- Inicializamos Alpine.js con dos variables: una para el modal de retiro y otra para la alerta de WhatsApp --}}
<div x-data="{ showWithdrawModal: false, showPhoneAlert: false }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    {{-- =========================================================================
         ALERTA FLOTANTE (TOAST) PARA WHATSAPP NO DISPONIBLE
         ========================================================================= --}}
    <div x-show="showPhoneAlert" style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-[-1rem]"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-[-1rem]"
         class="fixed top-24 right-4 sm:right-8 z-50 bg-rose-50 dark:bg-rose-500/10 border-l-4 border-red-600 p-4 rounded-r-xl shadow-lg flex items-center gap-3 max-w-sm">
        <span class="text-2xl">📵</span>
        <div>
            <h4 class="text-sm font-bold text-red-800 dark:text-red-400">Profesor no disponible</h4>
            <p class="text-xs text-red-600 dark:text-red-300">No se puede comunicar con el profesor en este momento. Por favor, intente más tarde.</p>
        </div>
    </div>

    {{-- =========================================================================
         HERO SECTION (Banner Principal unificado con la estética del sistema)
         ========================================================================= --}}
    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700/50 overflow-hidden mb-8 flex flex-col md:flex-row">

        {{-- Columna Izquierda: Imagen del curso --}}
        <div class="w-full md:w-2/5 lg:w-1/3 relative h-64 md:h-auto bg-gradient-to-br from-blue-900 to-blue-700 shrink-0">
            @if($course->thumbnail)
                <img src="{{ $course->thumbnail_url ?? asset($course->thumbnail) }}" class="w-full h-full object-cover shadow-inner opacity-90 mix-blend-overlay" alt="{{ $course->title }}">
            @else
                <div class="absolute inset-0 flex items-center justify-center text-8xl opacity-20">📚</div>
            @endif
            {{-- Efecto oscuro inferior para que contraste --}}
            <div class="absolute inset-0 bg-gradient-to-t from-blue-950/80 to-transparent"></div>
        </div>

        {{-- Columna Derecha: Información y Botones --}}
        <div class="p-8 sm:p-10 w-full md:w-3/5 lg:w-2/3 flex flex-col justify-center bg-gradient-to-r from-blue-900 to-blue-800 text-white">
            
            @if($course->category)
                <span class="inline-block px-3 py-1 bg-red-600/20 text-red-300 border border-red-500/30 text-[10px] font-black uppercase tracking-widest rounded-lg w-max mb-4 backdrop-blur-sm">
                    {{ $course->category->name }}
                </span>
            @endif

            <h1 class="text-3xl sm:text-4xl font-black tracking-tight mb-4 drop-shadow-md">
                {{ $course->title }}
            </h1>

            <p class="text-blue-100 mb-6 leading-relaxed line-clamp-3 text-sm md:text-base">
                {{ $course->description }}
            </p>

            {{-- Fichas de información (Estilo HACER modernizado) --}}
            <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-blue-50 mb-8">
                <span class="flex items-center gap-1.5 bg-white/10 backdrop-blur-sm px-3 py-2 rounded-xl border border-white/20">
                    👨🏽‍🏫 {{ $course->instructor->name ?? 'INCES' }}
                </span>
                <span class="flex items-center gap-1.5 bg-white/10 backdrop-blur-sm px-3 py-2 rounded-xl border border-white/20 capitalize">
                    📊 {{ $course->level_label ?? 'Básico' }}
                </span>
                <span class="flex items-center gap-1.5 bg-white/10 backdrop-blur-sm px-3 py-2 rounded-xl border border-white/20">
                    ⏱️ {{ $course->duration_hours ?? '40' }} horas
                </span>
                <span class="flex items-center gap-1.5 bg-white/10 backdrop-blur-sm px-3 py-2 rounded-xl border border-white/20">
                    🌐 Virtual
                </span>
            </div>

            {{-- Botones de Acción (Inscripción o Retiro) --}}
            <div>
                @if(isset($isEnrolled) && $isEnrolled)
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('student.courses.learn', $course) }}" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-blue-900 bg-white hover:bg-gray-100 rounded-xl shadow-lg transition-all hover:-translate-y-0.5 gap-2 w-full sm:w-auto">
                            📖 Continuar Aprendiendo
                        </a>
                        
                        <button @click="showWithdrawModal = true" type="button" class="inline-flex items-center justify-center px-6 py-3.5 text-sm font-bold text-red-300 bg-red-900/40 hover:bg-red-800/60 border border-red-500/30 rounded-xl transition-all hover:-translate-y-0.5 gap-2 w-full sm:w-auto">
                            🚪 Abandonar Curso
                        </button>
                    </div>

                    {{-- Modal de Retiro (Alpine.js) --}}
                    <div x-show="showWithdrawModal" style="display: none;" 
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm text-gray-900" 
                         x-transition.opacity>
                        
                        <div @click.away="showWithdrawModal = false" 
                             class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-2xl p-8 max-w-md w-full border border-gray-100 dark:border-slate-700"
                             x-transition:enter="transition ease-out duration-300" 
                             x-transition:enter-start="opacity-0 scale-95" 
                             x-transition:enter-end="opacity-100 scale-100">
                            
                            <div class="w-16 h-16 bg-red-50 dark:bg-red-500/10 text-red-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 shadow-inner border border-red-100 dark:border-red-900/30">
                                ⚠️
                            </div>
                            
                            <h3 class="text-2xl font-black text-center text-gray-900 dark:text-white mb-2">¿Estás seguro?</h3>
                            <p class="text-center text-gray-500 dark:text-slate-400 mb-8 text-sm">
                                Estás a punto de retirarte de <b class="text-gray-700 dark:text-slate-300">{{ $course->title }}</b>. Perderás todo tu progreso actual y tendrás que volver a inscribirte si deseas continuar luego.
                            </p>
                            
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button @click="showWithdrawModal = false" type="button" class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold rounded-xl transition-colors">
                                    Cancelar
                                </button>
                                
                                <form action="{{ route('student.courses.withdraw', $course->slug ?? $course->id) }}" method="POST" class="flex-1 m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-3 px-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-600/30 transition-colors">
                                        Sí, retirarme
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('student.courses.enroll', $course) }}" class="m-0">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5 gap-2 w-full sm:w-auto">
                            ✅ Inscribirme Ahora
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- =========================================================================
         CONTENIDO PRINCIPAL (Objetivos, Plan de Estudio y Perfil del MTP)
         ========================================================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Columna Izquierda (Plan de Estudio - 2/3) --}}
        <div class="lg:col-span-2 space-y-8">
            
            @if($course->objectives || $course->description)
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-slate-700/50">
                <h2 class="text-xl font-extrabold text-blue-900 dark:text-blue-400 mb-5 flex items-center gap-2">
                    <span class="text-2xl">🎯</span> Perfil de Egreso
                </h2>
                <div class="text-sm text-gray-600 dark:text-slate-300 whitespace-pre-line leading-relaxed">
                    {{ $course->objectives ?? 'El participante será capaz de aplicar las herramientas teóricas y prácticas adquiridas en esta formación para potenciar la producción nacional.' }}
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-slate-700/50">
                <h2 class="text-xl font-extrabold text-blue-900 dark:text-blue-400 mb-6 flex items-center gap-2">
                    <span class="text-2xl">📋</span> Plan de Estudio (Módulos)
                </h2>
                
                <div class="space-y-3">
                    @forelse($course->modules ?? [] as $module)
                    <div class="p-4 bg-gray-50 dark:bg-[#0f172a] rounded-2xl border border-gray-100 dark:border-slate-700 flex items-center justify-between hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center text-sm font-black shadow-inner border border-blue-200 dark:border-blue-800/50 shrink-0">
                                {{ $loop->iteration }}
                            </span>
                            <span class="font-bold text-gray-900 dark:text-white text-base uppercase">{{ $module->title }}</span>
                        </div>
                        <span class="text-xs font-bold text-gray-500 dark:text-slate-400 shrink-0 bg-white dark:bg-[#1e293b] px-3 py-1.5 rounded-lg border border-gray-100 dark:border-slate-600">
                            {{ $module->resources->count() ?? 0 }} recursos
                        </span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4 text-sm font-medium border border-dashed border-gray-300 dark:border-slate-700 rounded-xl">Aún no hay módulos publicados para esta formación.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Columna Derecha (Perfil del Instructor - 1/3) --}}
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 sticky top-24">
                
                {{-- Etiqueta del bloque --}}
                <div class="text-center mb-6">
                    <span class="inline-block bg-[#0088cc] text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest shadow-md">
                        Maestro Técnico Productivo
                    </span>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-24 h-24 mb-4 rounded-full p-1 bg-white dark:bg-[#0f172a] shadow-md border border-gray-100 dark:border-slate-700">
                        @if(isset($course->instructor->avatar_url) && $course->instructor->avatar_url)
                            <img src="{{ $course->instructor->avatar_url }}" class="w-full h-full rounded-full object-cover" alt="{{ $course->instructor->name ?? 'MTP' }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor->name ?? 'INCES') }}&background=0088cc&color=fff&size=128" class="w-full h-full rounded-full object-cover" alt="Avatar">
                        @endif
                    </div>
                    
                    <p class="font-extrabold text-gray-900 dark:text-white text-lg">{{ $course->instructor->name ?? 'Asignación Pendiente' }}</p>
                    
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-2 leading-relaxed px-4">
                        {{ $course->instructor->bio ?? 'Instructor capacitado y avalado por el INCES para la enseñanza de oficios productivos.' }}
                    </p>
                    
                    {{-- =========================================================================
                         LÓGICA DEL BOTÓN DE WHATSAPP
                         ========================================================================= --}}
                    @php
                        // Obtenemos el teléfono de la BD
                        $phone = $course->instructor->phone ?? null;
                        
                        // Limpiamos todo lo que no sea número (espacios, guiones, etc)
                        $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
                        
                        // Si el número empieza con 04 (ej. 0412, 0414) le ponemos el 58 de Venezuela
                        if($cleanPhone && substr($cleanPhone, 0, 2) === '04') {
                            $cleanPhone = '58' . substr($cleanPhone, 1);
                        }
                        
                        // Mensaje predeterminado que le llegará al profe
                        $instructorName = $course->instructor->name ?? 'Profesor';
                        $waMessage = "Saludos {$instructorName}, le escribo desde la plataforma IncesCampus. Soy estudiante y tengo una duda respecto a la formación: *{$course->title}*.";
                    @endphp

                    @if($cleanPhone)
                        {{-- Si TIENE un número válido, abre la API de WhatsApp en otra pestaña --}}
                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($waMessage) }}" target="_blank" class="mt-6 w-full inline-flex items-center justify-center px-4 py-3 text-sm font-bold text-white bg-[#25D366] hover:bg-[#128C7E] rounded-xl transition-all shadow-lg shadow-green-600/30 gap-2 hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.385 0 12.031c0 2.125.553 4.195 1.603 6.012L.15 23.85l5.961-1.564A11.956 11.956 0 0012.031 24c6.646 0 12.031-5.385 12.031-12.031S18.677 0 12.031 0zm0 22.016a9.92 9.92 0 01-5.088-1.393l-.364-.216-3.774.99.998-3.68-.236-.376a9.927 9.927 0 01-1.52-5.31C2.047 5.485 7.423.109 13.93.109c6.507 0 11.883 5.376 11.883 11.883s-5.376 11.883-11.883 11.883zm5.498-7.514c-.302-.151-1.782-.88-2.058-.98-.276-.1-.477-.151-.678.151-.201.302-.779.98-1.055 1.181-.276.201-.553.226-.855.075-.302-.151-1.272-.469-2.424-1.496-.897-.8-1.503-1.789-1.679-2.091-.176-.302-.019-.465.132-.616.135-.135.302-.352.453-.528.151-.176.201-.302.302-.503.1-.201.05-.377-.025-.528-.075-.151-.678-1.634-.93-2.238-.246-.59-.496-.51-.678-.519-.176-.009-.377-.009-.578-.009-.201 0-.528.075-.804.377-.276.302-1.055 1.031-1.055 2.514s1.08 2.916 1.231 3.117c.151.201 2.124 3.243 5.143 4.544 2.124.915 2.943.981 3.998.831 1.231-.176 3.774-1.544 4.302-3.033.528-1.489.528-2.766.377-3.033-.151-.267-.553-.418-.855-.569z"/></svg>
                            Contactar al Profesor
                        </a>
                    @else
                        {{-- Si NO TIENE número, cambia la variable de Alpine showPhoneAlert a true por 4 segundos --}}
                        <button @click="showPhoneAlert = true; setTimeout(() => showPhoneAlert = false, 4000)" type="button" class="mt-6 w-full inline-flex items-center justify-center px-4 py-3 text-sm font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-400 border border-gray-200 dark:border-slate-600 rounded-xl transition-all gap-2">
                            <svg class="w-5 h-5 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.385 0 12.031c0 2.125.553 4.195 1.603 6.012L.15 23.85l5.961-1.564A11.956 11.956 0 0012.031 24c6.646 0 12.031-5.385 12.031-12.031S18.677 0 12.031 0zm0 22.016a9.92 9.92 0 01-5.088-1.393l-.364-.216-3.774.99.998-3.68-.236-.376a9.927 9.927 0 01-1.52-5.31C2.047 5.485 7.423.109 13.93.109c6.507 0 11.883 5.376 11.883 11.883s-5.376 11.883-11.883 11.883zm5.498-7.514c-.302-.151-1.782-.88-2.058-.98-.276-.1-.477-.151-.678.151-.201.302-.779.98-1.055 1.181-.276.201-.553.226-.855.075-.302-.151-1.272-.469-2.424-1.496-.897-.8-1.503-1.789-1.679-2.091-.176-.302-.019-.465.132-.616.135-.135.302-.352.453-.528.151-.176.201-.302.302-.503.1-.201.05-.377-.025-.528-.075-.151-.678-1.634-.93-2.238-.246-.59-.496-.51-.678-.519-.176-.009-.377-.009-.578-.009-.201 0-.528.075-.804.377-.276.302-1.055 1.031-1.055 2.514s1.08 2.916 1.231 3.117c.151.201 2.124 3.243 5.143 4.544 2.124.915 2.943.981 3.998.831 1.231-.176 3.774-1.544 4.302-3.033.528-1.489.528-2.766.377-3.033-.151-.267-.553-.418-.855-.569z"/></svg>
                            Contactar al Profesor
                        </button>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection