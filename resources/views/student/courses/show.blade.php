@extends('layouts.app')
@section('title', $course->title)

@section('content')
{{-- Inicializamos Alpine.js con dos variables: modal de retiro y alerta de WhatsApp --}}
<div x-data="{ showWithdrawModal: false, showPhoneAlert: false }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    {{-- ALERTA FLOTANTE PARA WHATSAPP NO DISPONIBLE --}}
    <div x-show="showPhoneAlert" style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-[-1rem]"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-[-1rem]"
         class="fixed top-24 right-4 sm:right-8 z-50 bg-rose-50 dark:bg-rose-500/10 border-l-4 border-red-600 p-4 rounded-r-xl shadow-lg flex items-center gap-3 max-w-sm">
        <span class="text-red-600 shrink-0">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.207v-3.886A.75.75 0 0 0 13.5 1.5h-3a.75.75 0 0 0-.75.75v3.886m7.5 11.657v3.886A.75.75 0 0 1 16.5 22.5h-9a.75.75 0 0 1-.75-.75v-3.886m7.5-11.657v11.657m-7.5-11.657v11.657m7.5-11.657L3 3m11.25 4.864L21 3" /></svg>
        </span>
        <div>
            <h4 class="text-sm font-bold text-red-800 dark:text-red-400">Profesor no disponible</h4>
            <p class="text-xs text-red-600 dark:text-red-300">No se puede comunicar con el profesor en este momento. Por favor, intente más tarde.</p>
        </div>
    </div>

    {{-- HERO SECTION --}}
    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700/50 overflow-hidden mb-8 flex flex-col md:flex-row">

        {{-- Columna Izquierda: Imagen del curso con Lógica Inteligente --}}
        <div class="w-full md:w-2/5 lg:w-1/3 relative h-64 md:h-auto bg-gradient-to-br from-blue-900 to-blue-700 shrink-0">
            @php
                $thumbnailUrl = $course->thumbnail ? (str_starts_with($course->thumbnail, 'http') ? $course->thumbnail : asset('storage/' . $course->thumbnail)) : null;
            @endphp
            
            @if($thumbnailUrl)
                <img src="{{ $thumbnailUrl }}" class="w-full h-full object-cover shadow-inner opacity-90 mix-blend-overlay" alt="{{ $course->title }}">
            @else
                <div class="absolute inset-0 flex items-center justify-center opacity-30 text-white">
                    <svg class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-blue-950/80 to-transparent"></div>
        </div>

        {{-- Columna Derecha: Información y Botones --}}
        <div class="p-8 sm:p-10 w-full md:w-3/5 lg:w-2/3 flex flex-col justify-center bg-gradient-to-r from-blue-900 to-blue-800 text-white relative">
            
            @if($course->category)
                <span class="inline-block px-3 py-1 bg-red-600/20 text-red-300 border border-red-500/30 text-[10px] font-black uppercase tracking-widest rounded-lg w-max mb-4 backdrop-blur-sm shadow-sm">
                    {{ $course->category->name }}
                </span>
            @endif

            <h1 class="text-3xl sm:text-4xl font-black tracking-tight mb-4 drop-shadow-md">
                {{ $course->title }}
            </h1>

            <p class="text-blue-100 mb-6 leading-relaxed line-clamp-3 text-sm md:text-base">
                {{ $course->description }}
            </p>

            {{-- Fichas de información --}}
            <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-blue-50 mb-8">
                <span class="flex items-center gap-1.5 bg-white/10 backdrop-blur-sm px-3 py-2 rounded-xl border border-white/20">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    {{ $course->instructor->name ?? 'INCES' }}
                </span>
                <span class="flex items-center gap-1.5 bg-white/10 backdrop-blur-sm px-3 py-2 rounded-xl border border-white/20 capitalize">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                    {{ $course->level_label ?? 'Básico' }}
                </span>
                <span class="flex items-center gap-1.5 bg-white/10 backdrop-blur-sm px-3 py-2 rounded-xl border border-white/20">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    {{ $course->duration_hours ?? '40' }} horas
                </span>
            </div>

            {{-- Botones de Acción --}}
            <div>
                @if(isset($isEnrolled) && $isEnrolled)
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('student.courses.learn', $course) }}" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-blue-900 bg-white hover:bg-gray-100 rounded-xl shadow-lg transition-all hover:-translate-y-0.5 gap-2 w-full sm:w-auto">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                            Continuar Aprendiendo
                        </a>
                        
                        <button @click="showWithdrawModal = true" type="button" class="inline-flex items-center justify-center px-6 py-3.5 text-sm font-bold text-red-300 bg-red-900/40 hover:bg-red-800/60 border border-red-500/30 rounded-xl transition-all hover:-translate-y-0.5 gap-2 w-full sm:w-auto">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" /></svg>
                            Abandonar Curso
                        </button>
                    </div>

                    {{-- Modal de Retiro --}}
                    <div x-show="showWithdrawModal" style="display: none;" 
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm text-gray-900" 
                         x-transition.opacity>
                        
                        <div @click.away="showWithdrawModal = false" 
                             class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-2xl p-8 max-w-md w-full border border-gray-100 dark:border-slate-700"
                             x-transition:enter="transition ease-out duration-300" 
                             x-transition:enter-start="opacity-0 scale-95" 
                             x-transition:enter-end="opacity-100 scale-100">
                            
                            <div class="w-16 h-16 bg-red-50 dark:bg-red-500/10 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner border border-red-100 dark:border-red-900/30">
                                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            
                            <h3 class="text-2xl font-black text-center text-gray-900 dark:text-white mb-2">¿Estás seguro?</h3>
                            <p class="text-center text-gray-500 dark:text-slate-400 mb-8 text-sm">
                                Estás a punto de retirarte de <b class="text-gray-700 dark:text-slate-300">{{ $course->title }}</b>. Perderás todo tu progreso actual.
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
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            Inscribirme Ahora
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Columna Izquierda (Plan de Estudio) --}}
        <div class="lg:col-span-2 space-y-8">
            
            @if($course->objectives || $course->description)
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-slate-700/50">
                <h2 class="text-xl font-extrabold text-blue-900 dark:text-blue-400 mb-5 flex items-center gap-2">
                    <span class="text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                    </span>
                    Perfil de Egreso
                </h2>
                <div class="text-sm text-gray-600 dark:text-slate-300 whitespace-pre-line leading-relaxed">
                    {{ $course->objectives ?? 'El participante será capaz de aplicar las herramientas teóricas y prácticas adquiridas en esta formación para potenciar la producción nacional.' }}
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-slate-700/50">
                <h2 class="text-xl font-extrabold text-blue-900 dark:text-blue-400 mb-6 flex items-center gap-2">
                    <span class="text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                    </span>
                    Plan de Estudio (Módulos)
                </h2>
                
                <div class="space-y-3">
                    @forelse($course->modules ?? [] as $module)
                    <div x-data="{ open: false }" class="bg-gray-50 dark:bg-[#0f172a] rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden transition-all duration-300">
                        
                        {{-- Cabecera del Módulo --}}
                        <button @click="open = !open" type="button" class="w-full p-4 flex items-center justify-between hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                            <div class="flex items-center gap-4 text-left">
                                <span class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center text-sm font-black shadow-inner border border-blue-200 dark:border-blue-800/50 shrink-0">
                                    {{ $loop->iteration }}
                                </span>
                                <span class="font-bold text-gray-900 dark:text-white text-base uppercase">{{ $module->title }}</span>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-gray-500 dark:text-slate-400 shrink-0 bg-white dark:bg-[#1e293b] px-3 py-1.5 rounded-lg border border-gray-100 dark:border-slate-600">
                                    {{ $module->resources->count() ?? 0 }} recursos
                                </span>
                                <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </button>

                        {{-- Lista de Recursos --}}
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;" 
                             class="border-t border-gray-200 dark:border-slate-700 bg-white dark:bg-[#1e293b]">
                            
                            <ul class="divide-y divide-gray-100 dark:divide-slate-700/50">
                                @forelse($module->resources as $resource)
                                    <li class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                        
                                        <div class="flex items-center gap-3">
                                            <span class="shrink-0">
                                                @if(isset($resource->type) && $resource->type === 'pdf')
                                                    <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                                @elseif(isset($resource->type) && $resource->type === 'video')
                                                    <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" /></svg>
                                                @elseif(isset($resource->type) && $resource->type === 'link')
                                                    <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                                                @else
                                                    <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" /></svg>
                                                @endif
                                            </span>
                                            <span class="text-sm font-medium text-gray-700 dark:text-slate-300 line-clamp-2">
                                                {{ $resource->title }}
                                            </span>
                                        </div>

                                        {{-- BOTONES DE DESCARGA SEGUROS --}}
                                        <div class="shrink-0 flex items-center justify-end gap-2">
                                            @if(isset($isEnrolled) && $isEnrolled)
                                                <a href="{{ route('student.resources.show', $resource) }}" class="px-3 py-1.5 text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-lg transition-colors flex items-center gap-1">
                                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                                    Ver Recurso
                                                </a>

                                                @if($resource->is_downloadable)
                                                    <a href="{{ route('student.resources.download', $resource) }}" class="px-3 py-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-500/10 dark:hover:bg-blue-500/20 rounded-lg transition-colors flex items-center gap-1">
                                                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                                        Descargar
                                                    </a>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-red-600 bg-red-50 dark:bg-red-500/10 dark:text-red-400 rounded-lg border border-red-100 dark:border-red-900/30">
                                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                                                    Inscríbete para acceder
                                                </span>
                                            @endif
                                        </div>

                                    </li>
                                @empty
                                    <li class="p-4 text-xs text-gray-500 dark:text-slate-400 text-center font-medium">
                                        No hay recursos publicados en este módulo todavía.
                                    </li>
                                @endforelse
                            </ul>
                        </div>

                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4 text-sm font-medium border border-dashed border-gray-300 dark:border-slate-700 rounded-xl">Aún no hay módulos publicados para esta formación.</p>
                    @endforelse
            {{-- 🔥 SECCIÓN DE EVALUACIÓN FINAL DEL CURSO 🔥 --}}
            @if($course->quiz)
            <div class="bg-gradient-to-r from-blue-900 to-indigo-950 dark:from-slate-800 dark:to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-blue-800 dark:border-slate-700 relative overflow-hidden mt-8">
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-white shrink-0 border border-white/20 shadow-inner">
                            <svg class="w-8 h-8 text-blue-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12" /></svg>
                        </div>
                        <div>
                            <span class="px-3 py-1 bg-blue-500/20 text-blue-300 text-[10px] font-black uppercase tracking-widest rounded-md border border-blue-400/30 mb-2 inline-block">Evaluación Obligatoria</span>
                            <h3 class="text-xl font-black text-white tracking-tight">{{ $course->quiz->title }}</h3>
                            <p class="text-xs text-blue-200 dark:text-slate-300 mt-1">
                                ⏱️ Tiempo: <strong>{{ $course->quiz->time_limit }} min</strong> &nbsp;•&nbsp; 🎯 Nota mín: <strong>{{ $course->quiz->passing_score }}/20 pts</strong>
                            </p>
                        </div>
                    </div>

                    <div class="shrink-0 w-full md:w-auto">
                        @if($isEnrolled)
                            @if($course->quiz->is_active)
                                <a href="{{ route('student.quizzes.show', [$course->slug ?? $course->id, $course->quiz->id]) }}" class="w-full md:w-auto px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-black rounded-xl shadow-[0_0_20px_rgba(220,38,38,0.4)] transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm group">
                                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    <span>Presentar Evaluación Final</span>
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            @else
                                <span class="px-6 py-3.5 bg-gray-700/50 text-gray-300 font-bold rounded-xl text-xs flex items-center gap-2 border border-gray-600/50">
                                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                                    Evaluación Temporalmente Cerrada
                                </span>
                            @endif
                        @else
                            <span class="px-6 py-3.5 bg-white/10 text-blue-200 font-bold rounded-xl text-xs flex items-center gap-2 border border-white/20">
                                Inscríbete para presentar el examen
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Columna Derecha (Perfil del Instructor) --}}
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 sticky top-24">
                
                <div class="text-center mb-6">
                    <span class="inline-block bg-[#0088cc] text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest shadow-md">
                        Maestro Técnico Productivo
                    </span>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-24 h-24 mb-4 rounded-full p-1 bg-white dark:bg-[#0f172a] shadow-md border border-gray-100 dark:border-slate-700">
                        @php
                            $avatarUrl = $course->instructor->avatar_url ?? null;
                            if($avatarUrl && !str_starts_with($avatarUrl, 'http')) {
                                $avatarUrl = asset('storage/' . $avatarUrl);
                            }
                        @endphp
                        
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" class="w-full h-full rounded-full object-cover" alt="{{ $course->instructor->name ?? 'MTP' }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor->name ?? 'INCES') }}&background=0088cc&color=fff&size=128" class="w-full h-full rounded-full object-cover" alt="Avatar">
                        @endif
                    </div>
                    
                    <p class="font-extrabold text-gray-900 dark:text-white text-lg">{{ $course->instructor->name ?? 'Asignación Pendiente' }}</p>
                    
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-2 leading-relaxed px-4">
                        {{ $course->instructor->bio ?? 'Instructor capacitado y avalado por el INCES para la enseñanza de oficios productivos.' }}
                    </p>
                    
                    {{-- Lógica de WhatsApp --}}
                    @php
                        $phone = $course->instructor->phone ?? null;
                        $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
                        if($cleanPhone && substr($cleanPhone, 0, 2) === '04') {
                            $cleanPhone = '58' . substr($cleanPhone, 1);
                        }
                        $instructorName = $course->instructor->name ?? 'Profesor';
                        $waMessage = "Saludos {$instructorName}, le escribo desde la plataforma IncesCampus. Soy estudiante y tengo una duda respecto a la formación: *{$course->title}*.";
                    @endphp

                    @if($cleanPhone)
                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($waMessage) }}" target="_blank" class="mt-6 w-full inline-flex items-center justify-center px-4 py-3 text-sm font-bold text-white bg-[#25D366] hover:bg-[#128C7E] rounded-xl transition-all shadow-lg shadow-green-600/30 gap-2 hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.385 0 12.031c0 2.125.553 4.195 1.603 6.012L.15 23.85l5.961-1.564A11.956 11.956 0 0012.031 24c6.646 0 12.031-5.385 12.031-12.031S18.677 0 12.031 0zm0 22.016a9.92 9.92 0 01-5.088-1.393l-.364-.216-3.774.99.998-3.68-.236-.376a9.927 9.927 0 01-1.52-5.31C2.047 5.485 7.423.109 13.93.109c6.507 0 11.883 5.376 11.883 11.883s-5.376 11.883-11.883 11.883zm5.498-7.514c-.302-.151-1.782-.88-2.058-.98-.276-.1-.477-.151-.678.151-.201.302-.779.98-1.055 1.181-.276.201-.553.226-.855.075-.302-.151-1.272-.469-2.424-1.496-.897-.8-1.503-1.789-1.679-2.091-.176-.302-.019-.465.132-.616.135-.135.302-.352.453-.528.151-.176.201-.302.302-.503.1-.201.05-.377-.025-.528-.075-.151-.678-1.634-.93-2.238-.246-.59-.496-.51-.678-.519-.176-.009-.377-.009-.578-.009-.201 0-.528.075-.804.377-.276.302-1.055 1.031-1.055 2.514s1.08 2.916 1.231 3.117c.151.201 2.124 3.243 5.143 4.544 2.124.915 2.943.981 3.998.831 1.231-.176 3.774-1.544 4.302-3.033.528-1.489.528-2.766.377-3.033-.151-.267-.553-.418-.855-.569z"/></svg>
                            Contactar al Profesor
                        </a>
                    @else
                        <button @click="showPhoneAlert = true; setTimeout(() => showPhoneAlert = false, 4000)" type="button" class="mt-6 w-full inline-flex items-center justify-center px-4 py-3 text-sm font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-400 border border-gray-200 dark:border-slate-600 rounded-xl transition-all gap-2">
                            <svg class="w-5 h-5 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.385 0 12.031c0 2.125.553 4.195 1.603 6.012L.15 23.85l5.961-1.564A11.956 11.956 0 0012.031 24c6.646 0 12.031-5.385 12.031-12.031S18.677 0 12.031 0zm0 22.016a9.92 9.92 0 01-5.088-1.393l-.364-.216-3.774.99.998-3.68-.236-.376a9.927 9.927 0 01-1.52-5.31C2.047 5.485 7.423.109 13.93.109c6.507 0 11.883 5.376 11.883 11.883s-5.376 11.883-11.883 11.883zm5.498-7.514c-.302-.151-1.782-.88-2.058-.98-.276-.1-.477-.151-.678.151-.201.302-.779.98-1.055 1.181-.276.201-.553.226-.855.075-.302-.151-1.272-.469-2.424-1.496-.897-.8-1.503-1.789-1.679-2.091-.176-.302-.019-.465.132-.616.135-.135.302-.352.453-.528.151-.176.201-.302.302-.503.1-.201.05-.377-.025-.528-.075-.151-.678-1.634-.93-2.238-.246-.59-.496-.51-.678-.519-.176-.009-.377-.009-.578-.009-.201 0-.528.075-.804.377-.276.302-1.055 1.031-1.055 2.514s1.08 2.916 1.231 3.117c.151.201 2.124 3.243 5.143 4.544 2.124.915 2.943.981 3.998.831 1.231-.176 3.774-1.544 4.302-3.033.528-1.489.528-2.766.377-3.033-.151-.267-.553-.418-.855-.569z"/></svg>
                            Contactar al Profesor
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- EVALUACIÓN FINAL --}}
    @if(isset($isEnrolled) && $isEnrolled && $course->quiz && $course->quiz->is_active)
        <div class="mt-8 bg-gradient-to-r from-blue-900 to-blue-950 dark:from-slate-800 dark:to-slate-900 rounded-3xl shadow-xl border border-blue-800 dark:border-slate-700 p-8 sm:p-10 relative overflow-hidden">
            <div class="absolute inset-0 bg-white/5 opacity-20" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
            
            <div class="relative flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-6">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-white shadow-inner border border-white/20 shrink-0">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" /></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white tracking-tight mb-2">Evaluación Final: {{ $course->quiz->title }}</h2>
                        <p class="text-blue-200 dark:text-slate-300 font-medium text-sm max-w-xl">
                            Demuestra los conocimientos adquiridos en el curso. Tienes <strong>{{ $course->quiz->time_limit }} minutos</strong> para completarla. Calificación mínima aprobatoria: <strong>{{ $course->quiz->passing_score }} pts</strong>.
                        </p>
                    </div>
                </div>

                <div class="shrink-0 w-full md:w-auto text-center md:text-right">
                    @php
                        $enrollment = $course->students()->where('user_id', Auth::id())->first();
                    @endphp

                    @if($enrollment && $enrollment->pivot->status === 'approved')
                        <div class="bg-green-500/20 border border-green-500/50 rounded-xl px-8 py-4 text-center shadow-inner">
                            <p class="text-green-300 text-xs font-black uppercase tracking-widest mb-1 flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Aprobado
                            </p>
                            <p class="text-4xl font-black text-white">{{ $enrollment->pivot->final_grade }} <span class="text-xl text-green-200">/ 20</span></p>
                        </div>
                    @elseif($enrollment && $enrollment->pivot->status === 'failed')
                        <div class="bg-red-500/20 border border-red-500/50 rounded-xl px-8 py-4 text-center shadow-inner mb-4">
                            <p class="text-red-300 text-xs font-black uppercase tracking-widest mb-1 flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                Reprobado
                            </p>
                            <p class="text-4xl font-black text-white">{{ $enrollment->pivot->final_grade }} <span class="text-xl text-red-200">/ 20</span></p>
                        </div>
                        <p class="text-xs text-blue-200 font-bold mb-3">¿Deseas intentarlo de nuevo?</p>
                        <a href="{{ route('student.quizzes.show', [$course, $course->quiz]) }}" class="w-full md:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-600/30 hover:-translate-y-0.5">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                            Reintentar
                        </a>
                    @else
                        <a href="{{ route('student.quizzes.show', [$course, $course->quiz]) }}" class="w-full md:w-auto px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-black rounded-xl shadow-[0_0_20px_rgba(220,38,38,0.4)] transition-all hover:-translate-y-1 flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            <span>Iniciar Evaluación</span>
                            <svg class="w-5 h-5 shrink-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
@endsection