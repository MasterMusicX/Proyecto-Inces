@extends('layouts.app')
@section('title', $resource->title . ' | IncesCampus')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    {{-- =========================================================================
         MIGAS DE PAN (BREADCRUMB)
         ========================================================================= --}}
    <nav class="flex items-center gap-2 text-xs font-bold text-gray-500 dark:text-slate-400 mb-6 uppercase tracking-widest">
        <a href="{{ route('student.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Inicio</a>
        <span>/</span>
        <a href="{{ route('student.courses.show', $resource->course->slug ?? $resource->course->id) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors truncate max-w-[150px] sm:max-w-xs">
            {{ $resource->course->title ?? 'Curso' }}
        </a>
        <span>/</span>
        <span class="text-gray-800 dark:text-slate-200 truncate">{{ $resource->title }}</span>
    </nav>

    {{-- =========================================================================
         CABECERA DEL DOCUMENTO Y BOTÓN DE DESCARGA
         ========================================================================= --}}
    <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-slate-700/50 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-5 w-full md:w-auto">
            
            {{-- Ícono del Tipo de Archivo (SVG) --}}
            <div class="w-16 h-16 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-2xl flex items-center justify-center shadow-inner shrink-0 border border-red-100 dark:border-red-800/30">
                @if($resource->type === 'pdf')
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                @elseif($resource->isVideo())
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                @else
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                @endif
            </div>

            <div class="overflow-hidden">
                <h1 class="text-2xl font-black text-gray-900 dark:text-white truncate">{{ $resource->title }}</h1>
                @if($resource->description)
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 truncate">{{ $resource->description }}</p>
                @endif
                <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-gray-500 dark:text-slate-400 mt-3">
                    <span class="bg-gray-100 dark:bg-slate-800 px-2.5 py-1 rounded-md text-gray-700 dark:text-slate-300 uppercase tracking-widest">{{ $resource->type ?? 'DOCUMENTO' }}</span>
                    
                    @if($resource->file_size)
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg> 
                        {{ $resource->file_size_human ?? 'Desconocido' }}
                    </span>
                    @endif

                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" /></svg> 
                        {{ $resource->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>

        @php
            // Lógica para visualizar el archivo de forma segura y compatible en cualquier servidor (Railway/Docker/Local)
            if ($resource->type === 'url') {
                $fileUrl = $resource->external_url;
            } elseif ($resource->file_path && Str::startsWith($resource->file_path, ['http://', 'https://'])) {
                $fileUrl = $resource->file_path;
            } else {
                $fileUrl = route('student.resources.file', $resource);
            }
        @endphp

        @if($resource->is_downloadable || $resource->type === 'pdf')
        <a href="{{ route('student.resources.download', $resource) }}" class="w-full md:w-auto px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-600/30 shrink-0 hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
            Descargar Archivo
        </a>
        @endif
    </div>

    {{-- =========================================================================
         VISOR DEL DOCUMENTO / RECURSO
         ========================================================================= --}}
    <div class="bg-gray-900 rounded-3xl shadow-xl overflow-hidden mb-8 border border-gray-200 dark:border-slate-700 relative group">
        <div class="bg-gray-800 text-gray-400 text-[10px] font-bold uppercase tracking-widest px-4 py-2 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                <span class="ml-2">Visualizador INCES</span>
            </div>
            @if($resource->type === 'pdf' || $resource->type === 'url')
            <a href="{{ $fileUrl }}" target="_blank" class="hover:text-white transition-colors flex items-center gap-1">
                Abrir en nueva pestaña 
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
            </a>
            @endif
        </div>
        
        <div class="w-full bg-white dark:bg-gray-800 relative flex justify-center items-center">
            
            @if($resource->type === 'pdf')
                <iframe src="{{ $fileUrl }}" class="w-full h-[60vh] md:h-[75vh] border-none" title="Visor de Documento"></iframe>
            
            @elseif($resource->isVideo())
                <video controls class="w-full max-h-[70vh] bg-black">
                    <source src="{{ $fileUrl }}">
                    Tu navegador no soporta la reproducción de este video.
                </video>
            
            @elseif($resource->type === 'image')
                <img src="{{ $fileUrl }}" alt="{{ $resource->title }}" class="max-w-full max-h-[70vh] object-contain p-4">
            
            @elseif($resource->type === 'url')
                <div class="p-16 text-center w-full">
                    <div class="w-20 h-20 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Recurso Web Externo</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mb-6">{{ $resource->external_url }}</p>
                    <a href="{{ $resource->external_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-600/30">
                        Visitar Enlace Web
                    </a>
                </div>

            @else
                <div class="p-16 text-center w-full">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-slate-800 text-gray-400 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Archivo Descargable</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mb-6">Por su formato, este recurso no se puede previsualizar. Por favor descárgalo.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- =========================================================================
         SECCIÓN INFERIOR: IA Y ACCIONES (Sin emojis)
         ========================================================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Zona de Preguntas IA (Ocupa 2 columnas en PC) --}}
        <div class="lg:col-span-2 space-y-6">
            
            @if($resource->isDocument())
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50" x-data="docAI({{ $resource->id }})">
                <h3 class="text-sm font-black text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" /></svg>
                    Pregunta sobre este documento
                </h3>
                
                <form @submit.prevent="ask" class="relative flex items-center group">
                    <input type="text" x-model="question" :disabled="loading" placeholder="Ej: ¿Puedes resumirme la página 3?..." 
                           class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-600 rounded-2xl pl-5 pr-14 py-4 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-inner disabled:opacity-50">
                    <button type="submit" :disabled="loading || !question.trim()" class="absolute right-2 w-10 h-10 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white rounded-xl flex items-center justify-center transition-all shadow-md active:scale-95 disabled:active:scale-100">
                        <svg x-show="!loading" class="w-5 h-5 -ml-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                        <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    </button>
                </form>

                <div x-show="answer" x-transition.opacity class="mt-4 p-5 bg-blue-50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-800/30">
                    <p class="text-xs font-black text-blue-800 dark:text-blue-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" /></svg>
                        Respuesta del Asistente
                    </p>
                    <p class="text-sm text-blue-900 dark:text-blue-200 leading-relaxed" x-text="answer"></p>
                </div>
            </div>
            @endif

            {{-- Análisis Pendiente / General --}}
            <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/30 rounded-3xl p-6 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-800/50 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 animate-[spin_3s_linear_infinite]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-blue-900 dark:text-blue-300">Análisis IA en cola</h4>
                    <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">El resumen automático de este documento se procesará en breve.</p>
                </div>
            </div>
        </div>

        {{-- Zona de Botones de Acción (Ocupa 1 columna en PC) --}}
        <div class="space-y-4">
            <a href="{{ route('student.courses.learn', $resource->course->slug ?? $resource->course->id) }}" class="group bg-white dark:bg-[#1e293b] p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 flex items-center gap-4 hover:border-red-500 dark:hover:border-red-500 transition-all hover:shadow-md">
                <div class="w-12 h-12 bg-gray-50 dark:bg-[#0f172a] text-gray-500 dark:text-slate-400 rounded-xl flex items-center justify-center group-hover:bg-red-50 dark:group-hover:bg-red-900/30 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-sm font-black text-gray-900 dark:text-white group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">Volver al curso</h4>
                    <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 truncate mt-0.5">{{ $resource->course->title ?? 'Aula Virtual' }}</p>
                </div>
            </a>

            <a href="{{ route('student.chatbot') }}" class="group bg-white dark:bg-[#1e293b] p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 flex items-center gap-4 hover:border-blue-500 dark:hover:border-blue-500 transition-all hover:shadow-md">
                <div class="w-12 h-12 bg-gray-50 dark:bg-[#0f172a] text-gray-500 dark:text-slate-400 rounded-xl flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/30 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" /></svg>
                </div>
                <div>
                    <h4 class="text-sm font-black text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">¿Tienes dudas generales?</h4>
                    <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 mt-0.5">Abrir Asistente IA Global</p>
                </div>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('docAI', (resourceId) => ({
        question: '',
        answer: '',
        loading: false,
        async ask() {
            if (!this.question.trim()) return;
            this.loading = true;
            this.answer = '';
            try {
                const r = await fetch(`/api/documents/${resourceId}/ask`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ question: this.question })
                });
                const data = await r.json();
                this.answer = data.success ? data.response : 'Error al procesar la pregunta. Intente nuevamente.';
            } catch(e) {
                this.answer = 'Error de conexión con el Asistente IA.';
            } finally {
                this.loading = false;
                this.question = ''; // Limpiar input después de preguntar
            }
        }
    }))
});
</script>
@endpush
@endsection
