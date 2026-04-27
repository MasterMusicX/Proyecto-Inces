@extends('layouts.app')
@section('title', 'Mi Panel')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div x-data="{ showGuide: localStorage.getItem('hideIncesGuide') !== 'true' }">
        <div x-show="showGuide" style="display: none;"
             x-transition.opacity
             class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            
            <div @click.away="showGuide = false; localStorage.setItem('hideIncesGuide', 'true')" 
                 x-show="showGuide"
                 x-transition:enter="transform transition ease-out duration-300"
                 x-transition:enter-start="scale-95 opacity-0"
                 x-transition:enter-end="scale-100 opacity-100"
                 class="bg-white dark:bg-[#1e293b] w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-slate-700">
                
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-500/20 text-2xl flex items-center justify-center rounded-2xl">🚀</div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white">¡Bienvenido a Inces Campus!</h2>
                            <p class="text-gray-500 dark:text-slate-400 text-sm">Guía rápida para tu formación profesional.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-gray-50 dark:bg-[#0f172a]/50 border border-gray-100 dark:border-slate-800">
                            <span class="font-bold text-blue-600 dark:text-blue-400 text-lg">01. Explora</span>
                            <p class="text-xs text-gray-600 dark:text-slate-400 mt-1">Ve al <b>Catálogo</b> para inscribirte en los cursos disponibles del sector construcción.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-gray-50 dark:bg-[#0f172a]/50 border border-gray-100 dark:border-slate-800">
                            <span class="font-bold text-blue-600 dark:text-blue-400 text-lg">02. Aprende</span>
                            <p class="text-xs text-gray-600 dark:text-slate-400 mt-1">Dentro de cada curso encontrarás materiales, videos y asignaciones de tus instructores.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-gray-50 dark:bg-[#0f172a]/50 border border-gray-100 dark:border-slate-800">
                            <span class="font-bold text-blue-600 dark:text-blue-400 text-lg">03. Pregunta</span>
                            <p class="text-xs text-gray-600 dark:text-slate-400 mt-1">Usa nuestra <b>IA de Búsqueda</b> para resolver dudas técnicas sobre cualquier tema.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-gray-50 dark:bg-[#0f172a]/50 border border-gray-100 dark:border-slate-800">
                            <span class="font-bold text-blue-600 dark:text-blue-400 text-lg">04. Certifícate</span>
                            <p class="text-xs text-gray-600 dark:text-slate-400 mt-1">Al completar todas las tareas, recibirás tu certificación avalada por el INCES.</p>
                        </div>
                    </div>

                    <button @click="showGuide = false; localStorage.setItem('hideIncesGuide', 'true')" 
                            class="w-full mt-8 py-4 bg-blue-800 hover:bg-blue-900 text-white font-bold rounded-2xl transition-all shadow-lg shadow-blue-800/30 active:scale-95">
                        ¡Entendido, vamos a estudiar!
                    </button>
                </div>
            </div>
        </div>
    </div>
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