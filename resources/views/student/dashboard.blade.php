@extends('layouts.app')
@section('title', 'Mi Panel')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-3xl p-8 sm:p-10 mb-10 shadow-xl relative overflow-hidden text-white border border-blue-800">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-red-600/20 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
            <div>
                <h1 class="text-3xl sm:text-4xl font-black mb-2 flex items-center justify-center md:justify-start gap-3">
                    ¡Hola de nuevo, {{ explode(' ', Auth::user()->name)[0] }}! <span class="text-4xl animate-wave">👋</span>
                </h1>
                <p class="text-blue-100 text-lg">Tienes un progreso increíble. ¡Sigue así y obtén tu certificado!</p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-center min-w-[120px] shadow-inner">
                <span class="block text-4xl font-black text-white">{{ $enrolledCourses->count() ?? 0 }}</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-blue-200 mt-1 block">Inscritos</span>
            </div>
        </div>
    </div>

    <div class="mb-12">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white">Continuar Aprendiendo</h2>
            <a href="{{ route('student.courses.catalog') }}" class="text-sm font-bold text-red-600 hover:text-red-700 dark:text-red-500 transition-colors bg-red-50 dark:bg-red-500/10 px-4 py-2 rounded-xl">
                Explorar catálogo &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($enrolledCourses ?? [] as $course)
                <div class="bg-white dark:bg-[#1e293b] rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-5 flex flex-col transition-all hover:shadow-lg hover:-translate-y-1">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-16 h-16 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0">
                            <img src="{{ $course->thumbnail ?? 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070' }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-red-600 uppercase tracking-widest">{{ $course->category->name ?? 'General' }}</span>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white line-clamp-2 leading-tight mt-1">{{ $course->title }}</h3>
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="flex justify-between text-xs font-bold text-gray-500 mb-2">
                            <span>Progreso</span>
                            <span class="text-blue-600">{{ $course->pivot->progress_percentage ?? 0 }}%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden mb-4">
                            <div class="h-full bg-blue-600 rounded-full" style="width: {{ $course->pivot->progress_percentage ?? 0 }}%"></div>
                        </div>
                        <a href="{{ route('student.courses.show', $course->id) }}" class="block w-full py-2.5 text-center text-sm font-bold text-white bg-blue-800 hover:bg-blue-900 rounded-xl transition-colors shadow-sm">
                            Ir a clases
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-[#1e293b] rounded-3xl border border-dashed border-gray-300 dark:border-slate-700 p-10 text-center">
                    <div class="text-5xl mb-4">📚</div>
                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2">Aún no te has inscrito en ningún curso</h3>
                    <p class="text-gray-500 dark:text-slate-400 mb-6">Tu panel de aprendizaje está esperando por ti. ¡Anímate a empezar!</p>
                    <a href="{{ route('student.courses.catalog') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-red-600/30 transition-transform hover:-translate-y-1">
                        Ir al Catálogo de Cursos
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <div>
        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-6">Cursos Destacados</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredCourses ?? [] as $course)
                <div class="group bg-white dark:bg-[#1e293b] rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-slate-700/50 hover:shadow-lg transition-all">
                    <div class="h-32 overflow-hidden relative">
                        <img src="{{ $course->thumbnail ?? 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-4 flex flex-col">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 mb-3 group-hover:text-blue-600 transition-colors">{{ $course->title }}</h3>
                        <a href="{{ route('student.courses.show', $course->slug ?? $course->id) }}" class="mt-auto px-4 py-2 bg-gray-50 hover:bg-blue-800 text-gray-700 hover:text-white dark:bg-slate-800 dark:text-slate-300 rounded-lg text-xs font-bold text-center transition-colors">
                            Ver detalles
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-slate-400 text-sm">No hay cursos destacados por el momento.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection