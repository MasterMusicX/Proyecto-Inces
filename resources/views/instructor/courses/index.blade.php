@extends('layouts.app')
@section('title', 'Mis Cursos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 animate-fade-in-up">

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Mis Cursos</h1>
            <p class="text-gray-500 dark:text-slate-400 mt-1">Gestiona y administra el contenido de tus clases.</p>
        </div>
        <a href="{{ route('instructor.courses.create') ?? '#' }}" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-0.5 flex items-center gap-2 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Curso
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col">

                <div class="h-36 bg-gradient-to-br from-blue-900 to-blue-600 relative overflow-hidden flex items-center justify-center">
                    
                    @if($course->thumbnail)
                        {{-- 🔥 LÓGICA INTELIGENTE: Si viene de ImgBB (http) la pone directa, si no, usa el storage local 🔥 --}}
                        <img src="{{ str_starts_with($course->thumbnail, 'http') ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-50 transition-transform duration-500 group-hover:scale-105" alt="{{ $course->title }}">
                    @else
                        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>
                        <div class="opacity-30 drop-shadow-md text-white">
                            <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                            </svg>
                        </div>
                    @endif
                    
                    <div class="absolute top-4 right-4">
                        @if($course->status === 'published') 
                            <span class="px-3 py-1 bg-white/90 dark:bg-blue-900/90 backdrop-blur-sm text-blue-800 dark:text-blue-400 text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm border border-blue-200 dark:border-blue-800 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                Publicado
                            </span>
                        @else
                            <span class="px-3 py-1 bg-white/90 dark:bg-amber-900/90 backdrop-blur-sm text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm border border-amber-100 dark:border-amber-800 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                Borrador
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-blue-800 dark:group-hover:text-blue-400 transition-colors leading-tight">
                        {{ $course->title }}
                    </h3>

                    <div class="flex items-center gap-4 mt-auto pt-4 border-t border-gray-100 dark:border-slate-700/50">
                        <div class="flex items-center gap-1.5 text-gray-500 dark:text-slate-400" title="Estudiantes inscritos">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span class="text-sm font-bold">{{ $course->enrollments_count ?? 0 }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-500 dark:text-slate-400" title="Módulos creados">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span class="text-sm font-bold">{{ $course->modules_count ?? 0 }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-500 dark:text-slate-400" title="Recursos subidos">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-sm font-bold">{{ $course->resources_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-[#0f172a]/50 border-t border-gray-100 dark:border-slate-700/50 flex gap-3">
                    <a href="{{ route('instructor.courses.show', $course) }}" class="flex items-center justify-center flex-1 py-2.5 text-xs font-bold text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5 opacity-70" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        Ver Detalle
                    </a>
                    <a href="{{ route('instructor.courses.resources.index', $course) }}" class="flex items-center justify-center flex-1 py-2.5 text-xs font-bold text-white bg-blue-800 hover:bg-blue-900 dark:bg-blue-600 dark:hover:bg-blue-700 rounded-xl transition-all shadow-sm shadow-blue-800/20">
                        <svg class="w-4 h-4 mr-1.5 opacity-90" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" /></svg>
                        Recursos
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white dark:bg-[#1e293b] rounded-3xl border border-dashed border-gray-300 dark:border-slate-700 shadow-sm">
                <div class="w-24 h-24 mx-auto bg-blue-50 dark:bg-slate-800/50 text-blue-500 dark:text-blue-400 rounded-full flex items-center justify-center mb-6 shadow-inner border border-blue-100 dark:border-slate-700">
                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                    </svg>
                </div>
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-2">No tienes cursos creados aún</h3>
                <p class="text-gray-500 dark:text-slate-400 text-base mb-8 max-w-md mx-auto">Cuando crees un nuevo curso de formación, aparecerá aquí.</p>
                <a href="{{ route('instructor.courses.create') ?? '#' }}" class="inline-flex items-center gap-2 px-8 py-4 bg-red-600 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-600/30 transition-all hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Crear mi primer curso
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
