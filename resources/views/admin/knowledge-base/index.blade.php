@extends('layouts.app')
@section('title', 'Base de Conocimiento IA')

@section('content')
<div class="max-w-7xl mx-auto pb-10">
    
    <div class="flex items-center justify-between mb-8 animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Base de Conocimiento IA</h1>
            <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1">El Chatbot consulta esta base para responder preguntas frecuentes.</p>
        </div>
        <a href="{{ route('admin.knowledge-base.create') }}" class="inline-flex items-center px-6 py-3 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Nueva Entrada
        </a>
    </div>

    <div class="bg-white dark:bg-[#1e293b] p-4 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 mb-8 animate-fade-in-up" style="animation-delay: 100ms;">
        <form action="{{ route('admin.knowledge-base.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por pregunta o palabras clave..." 
                       class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
            <select name="category" class="md:w-48 bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer">
                <option value="">Todas las categorías</option>
                @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-3 bg-gray-900 dark:bg-slate-700 hover:bg-gray-800 dark:hover:bg-slate-600 text-white font-bold rounded-xl text-sm transition-colors">
                Filtrar
            </button>
            @if(request()->anyFilled(['search', 'category']))
                <a href="{{ route('admin.knowledge-base.index') }}" class="px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-xl text-sm font-bold text-center transition-colors">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden animate-fade-in-up" style="animation-delay: 200ms;">
        
        @forelse($entries ?? [] as $entry)
            <div class="p-6 border-b border-gray-100 dark:border-slate-700/50 last:border-b-0 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors group">
                <div class="flex items-start gap-5">
                    
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm border border-gray-100 dark:border-slate-700/50 flex-shrink-0 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.829 1.508-2.316a7.5 7.5 0 1 0-7.536 0c.85.487 1.508 1.333 1.508 2.316V18" /></svg>
                    </div>

                    <div class="flex-grow">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $entry->question }}
                            </h3>
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                <a href="{{ route('admin.knowledge-base.edit', $entry->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-xl transition-colors" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path></svg>
                                </a>
                                <form action="{{ route('admin.knowledge-base.destroy', $entry->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('¿Seguro que deseas eliminar esta pregunta?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-xl transition-colors" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-slate-400 mt-1 mb-4 line-clamp-2">
                            {{ Str::limit($entry->answer, 200) }}
                        </p>

                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs font-medium text-gray-500 dark:text-slate-500 border-t border-gray-100 dark:border-slate-700/50 pt-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 font-bold uppercase tracking-wider text-[10px]">
                                {{ $entry->category ?? 'General' }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /></svg>
                                {{ $entry->views ?? 0 }} vistas
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                {{ $entry->updated_at->diffForHumans() }}
                            </span>
                            @if($entry->tags)
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /></svg>
                                    @foreach($entry->tags as $tag)
                                        <span class="px-2 py-0.5 bg-gray-100 dark:bg-slate-700 rounded-md">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

           @empty
            <div class="p-16 text-center">
                <div class="w-20 h-20 mx-auto bg-gray-50 dark:bg-[#0f172a] rounded-full flex items-center justify-center text-4xl mb-4 border border-gray-100 dark:border-slate-700/50">
                    <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Base de conocimiento vacía</h3>
                <p class="text-gray-500 dark:text-slate-400 text-sm">Aún no hay preguntas registradas. ¡Crea la primera para alimentar a la IA!</p>
            </div>
        @endforelse

    </div>
    
    <div class="mt-8">
        {{ $entries->links() }}
    </div>

</div>
@endsection
