@extends('layouts.app')
@section('title', 'Gestión de Cursos')

@section('content')
<div class="max-w-7xl mx-auto flex flex-col gap-6">

    {{-- Encabezado y Botón Nuevo --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-1">Cursos</h1>
            <p class="text-sm font-medium text-gray-500 dark:text-slate-400">
                {{ $courses->total() ?? 0 }} cursos en total en la plataforma
            </p>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-bold rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-0.5 whitespace-nowrap">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Curso
        </a>
    </div>

    {{-- Barra de Filtros y Búsqueda --}}
    <div class="bg-white dark:bg-[#1e293b] rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700/50 animate-fade-in-up" style="animation-delay: 100ms;">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            
            {{-- Buscador de Texto --}}
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar curso por nombre..." 
                    class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-transparent rounded-xl pl-11 pr-4 py-3 text-sm text-gray-900 dark:text-slate-200 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:outline-none transition-all">
            </div>
            
            {{-- Selector de Estado --}}
            <div class="md:w-48 relative">
                <select name="status" class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-transparent rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:outline-none transition-all appearance-none cursor-pointer">
                    <option value="">Todos los estados</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicados</option>
                    <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Borradores</option>
                    <option value="archived"  {{ request('status') === 'archived'  ? 'selected' : '' }}>Archivados</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            {{-- Botón Filtrar --}}
            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold rounded-xl bg-gray-900 dark:bg-slate-700 text-white hover:bg-gray-800 dark:hover:bg-slate-600 transition-colors whitespace-nowrap shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filtrar
            </button>
        </form>
    </div>

   {{-- Grid de Tarjetas de Cursos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($courses as $i => $course)
        <div class="bg-white dark:bg-[#1e293b] rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden flex flex-col transition-all hover:-translate-y-1 hover:shadow-md group animate-fade-in-up" style="animation-delay: {{ ($i + 2) * 100 }}ms;">
            
            {{-- Imagen / Portada del Curso --}}
            <div class="h-40 bg-gradient-to-br from-blue-50 dark:from-blue-500/10 dark:to-blue-900/20 relative overflow-hidden flex items-center justify-center border-b border-gray-100 dark:border-slate-700/50">
                @if($course->thumbnail)
                    {{-- 🔥 LÓGICA INTELIGENTE APLICADA AQUÍ 🔥 --}}
                    <img src="{{ str_starts_with($course->thumbnail, 'http') ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}" 
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                         alt="{{ $course->title }}">
                    <div style="display:none;" class="absolute inset-0 flex items-center justify-center opacity-30 dark:opacity-20 mix-blend-overlay">
                        <svg class="w-16 h-16 text-blue-800 dark:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                    </div>
                @else
                    <div class="absolute inset-0 flex items-center justify-center opacity-30 dark:opacity-20 mix-blend-overlay text-blue-800 dark:text-blue-200">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                    </div>
                @endif
                
                {{-- Badge de Estado (Superpuesto en la imagen) --}}
                <div class="absolute top-3 right-3">
                    @if($course->status === 'published')
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg bg-blue-100/90 text-blue-700 dark:bg-blue-500/90 dark:text-white backdrop-blur-sm shadow-sm border border-blue-200/50 dark:border-blue-400/50 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            Publicado
                        </span>
                    @elseif($course->status === 'draft')
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg bg-amber-100/90 text-amber-700 dark:bg-amber-500/90 dark:text-white backdrop-blur-sm shadow-sm border border-amber-200/50 dark:border-amber-400/50 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            Borrador
                        </span>
                    @else
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg bg-gray-100/90 text-gray-700 dark:bg-gray-500/90 dark:text-white backdrop-blur-sm shadow-sm border border-gray-200/50 dark:border-gray-400/50 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                            Archivado
                        </span>
                    @endif
                </div>
            </div>

            {{-- Información del Curso --}}
            <div class="p-5 flex-1 flex flex-col">
                <h3 class="font-bold text-gray-900 dark:text-white text-base mb-2 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                    {{ $course->title }}
                </h3>
                
                <div class="mt-auto pt-2 space-y-2">
                    <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"></path></svg>
                        <span class="truncate">{{ $course->instructor->name ?? 'Sin Instructor' }}</span>
                    </p>
                    <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path></svg>
                        {{ $course->enrollments_count ?? 0 }} estudiantes inscritos
                    </p>
                </div>
                
                {{-- Botones de Acción --}}
                <div class="flex items-center gap-2 mt-5 pt-4 border-t border-gray-100 dark:border-slate-700/50">
                    <a href="{{ route('admin.courses.edit', $course) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 text-xs font-bold rounded-lg bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-slate-200 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-500/20 dark:hover:text-blue-400 transition-colors border border-gray-200/50 dark:border-slate-600/50">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Editar
                    </a>
                    
                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este curso de forma permanente?')">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="inline-flex justify-center items-center p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/20 dark:hover:text-rose-400 rounded-lg transition-colors border border-transparent hover:border-rose-200 dark:hover:border-rose-500/30" title="Eliminar Curso">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white dark:bg-[#1e293b] rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-12 flex flex-col items-center justify-center text-center animate-fade-in-up">
            <div class="w-20 h-20 bg-gray-50 dark:bg-[#0f172a] rounded-full flex items-center justify-center mb-4 border border-gray-100 dark:border-slate-700/50">
                <svg class="w-10 h-10 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">No se encontraron cursos</h3>
            <p class="text-gray-500 dark:text-slate-400 max-w-sm text-sm">No hay cursos que coincidan con los filtros actuales o la plataforma aún no tiene cursos registrados.</p>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.courses.index') }}" class="mt-4 text-blue-600 dark:text-blue-400 text-sm font-semibold hover:underline">Limpiar filtros</a>
            @endif
        </div>
        @endforelse
    </div>
    
    {{-- Paginación --}}
    @if($courses->hasPages())
    <div class="mt-2 bg-white dark:bg-[#1e293b] p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50">
        {{ $courses->links() }}
    </div>
    @endif

</div>
@endsection
