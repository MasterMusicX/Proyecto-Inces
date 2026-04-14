@extends('layouts.app')
@section('title', $course->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden mb-8 flex flex-col md:flex-row">

        <div class="w-full md:w-2/5 lg:w-1/3 relative h-64 md:h-auto bg-gradient-to-br from-blue-600 to-blue-800 dark:from-slate-800 dark:to-slate-900 shrink-0">
            @if($course->thumbnail)
                <img src="{{ $course->thumbnail_url }}" class="w-full h-full object-cover shadow-inner" alt="{{ $course->title }}">
            @else
                <div class="absolute inset-0 flex items-center justify-center text-8xl opacity-20">📚</div>
            @endif
        </div>

        <div class="p-8 sm:p-10 w-full md:w-3/5 lg:w-2/3 flex flex-col justify-center">
            @if($course->category)
                <span class="inline-block px-3 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-blue-100 dark:border-blue-800/50 w-max mb-4">
                    {{ $course->category->name }}
                </span>
            @endif

            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">
                {{ $course->title }}
            </h1>

            <p class="text-gray-600 dark:text-slate-300 mb-6 leading-relaxed line-clamp-3">
                {{ $course->description }}
            </p>

            <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-gray-500 dark:text-slate-400 mb-8">
                <span class="flex items-center gap-1.5 bg-gray-50 dark:bg-[#0f172a] px-3 py-2 rounded-xl border border-gray-100 dark:border-slate-700">
                    👨🏽‍🏫 {{ $course->instructor->name }}
                </span>
                <span class="flex items-center gap-1.5 bg-gray-50 dark:bg-[#0f172a] px-3 py-2 rounded-xl border border-gray-100 dark:border-slate-700 capitalize">
                    📊 {{ $course->level_label }}
                </span>
                <span class="flex items-center gap-1.5 bg-gray-50 dark:bg-[#0f172a] px-3 py-2 rounded-xl border border-gray-100 dark:border-slate-700">
                    ⏱️ {{ $course->duration_hours }} horas
                </span>
                <span class="flex items-center gap-1.5 bg-gray-50 dark:bg-[#0f172a] px-3 py-2 rounded-xl border border-gray-100 dark:border-slate-700">
                    👥 {{ $course->enrolled_count }} inscritos
                </span>
                <span class="flex items-center gap-1.5 bg-gray-50 dark:bg-[#0f172a] px-3 py-2 rounded-xl border border-gray-100 dark:border-slate-700">
                    📦 {{ $course->modules->count() }} módulos
                </span>
            </div>

            <div>
                @if($isEnrolled)
                    <a href="{{ route('student.courses.learn', $course) }}" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-0.5 gap-2 w-full sm:w-auto">
                        📖 Continuar Aprendiendo
                    </a>
                @else
                    <form method="POST" action="{{ route('student.courses.enroll', $course) }}" class="m-0">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5 gap-2 w-full sm:w-auto">
                            ✅ Inscribirme Gratis
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            @if($course->objectives)
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-slate-700/50">
                <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                    <span class="text-2xl">🎯</span> Objetivos del Curso
                </h2>
                <div class="text-sm text-gray-600 dark:text-slate-300 whitespace-pre-line leading-relaxed">
                    {{ $course->objectives }}
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-slate-700/50">
                <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="text-2xl">📋</span> Contenido del Curso
                </h2>
                
                <div class="space-y-3">
                    @forelse($course->modules as $module)
                    <div class="p-4 bg-gray-50 dark:bg-[#0f172a] rounded-2xl border border-gray-100 dark:border-slate-700 flex items-center justify-between hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center text-sm font-black shadow-inner border border-blue-200 dark:border-blue-800/50 shrink-0">
                                {{ $loop->iteration }}
                            </span>
                            <span class="font-bold text-gray-900 dark:text-white text-base">{{ $module->title }}</span>
                        </div>
                        <span class="text-xs font-bold text-gray-500 dark:text-slate-400 shrink-0 bg-white dark:bg-[#1e293b] px-3 py-1.5 rounded-lg border border-gray-100 dark:border-slate-600">
                            {{ $module->resources->count() }} recursos
                        </span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4 text-sm font-medium">Aún no hay módulos publicados.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700/50 sticky top-6">
                <h3 class="text-lg font-extrabold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="text-xl">👩‍🏫</span> Tu Instructor
                </h3>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-24 h-24 mb-4 rounded-full p-1 bg-white dark:bg-[#0f172a] shadow-md border border-gray-100 dark:border-slate-700">
                        @if($course->instructor->avatar_url)
                            <img src="{{ $course->instructor->avatar_url }}" class="w-full h-full rounded-full object-cover" alt="{{ $course->instructor->name }}">
                        @else
                            <div class="w-full h-full rounded-full bg-blue-800 text-white flex items-center justify-center text-3xl font-black">
                                {{ strtoupper(substr($course->instructor->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    
                    <p class="font-extrabold text-gray-900 dark:text-white text-lg">{{ $course->instructor->name }}</p>
                    
                    @if($course->instructor->bio)
                        <p class="text-sm text-gray-500 dark:text-slate-400 mt-2 leading-relaxed">
                            {{ Str::limit($course->instructor->bio, 100) }}
                        </p>
                    @endif
                    
                    <a href="mailto:{{ $course->instructor->email ?? '' }}" class="mt-6 w-full inline-flex items-center justify-center px-4 py-2.5 text-xs font-bold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 hover:bg-blue-100 dark:hover:bg-blue-500/20 border border-blue-100 dark:border-blue-800/50 rounded-xl transition-colors gap-2">
                        ✉️ Enviar Mensaje
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection