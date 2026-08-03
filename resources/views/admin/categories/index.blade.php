@extends('layouts.app')
@section('title', 'Gestión de Categorías')

@section('content')
<div class="max-w-7xl mx-auto pb-10">
    
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Categorías de Cursos</h1>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1">Organiza el contenido de IncesCampus por áreas de formación.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6 animate-fade-in-up" style="animation-delay: 100ms;">
            
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 dark:bg-[#0f172a]/80 border-b border-gray-100 dark:border-slate-700/50 text-[11px] uppercase tracking-widest text-gray-500 dark:text-slate-400 font-bold">
                                <th class="p-4 pl-6 w-1/2">Categoría</th>
                                <th class="p-4 text-center">Cursos</th>
                                <th class="p-4 pr-6 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                            
                            @forelse($categories ?? [] as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors group">
                                    <td class="p-4 pl-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-sm border border-gray-100 dark:border-slate-700/50 flex-shrink-0 transition-transform group-hover:scale-105" 
                                                 style="background-color: {{ $category->color }}15; border-color: {{ $category->color }}30;">
                                                <svg class="w-6 h-6" style="color: {{ $category->color }};" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.659A2.25 2.25 0 0 0 9.568 3Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 dark:text-white text-base">{{ $category->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-slate-400 line-clamp-1 mt-0.5">{{ $category->description ?? 'Sin descripción' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300">
                                            {{ $category->courses_count ?? 0 }}
                                        </span>
                                    </td>
                                    
                                    <td class="p-4 pr-6 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                               class="inline-flex items-center justify-center p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-xl transition-colors" title="Editar Categoría">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-xl transition-colors" title="Eliminar Categoría">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-16 text-center">
                                        <div class="w-20 h-20 mx-auto bg-gray-50 dark:bg-[#0f172a] rounded-full flex items-center justify-center text-4xl mb-4 border border-gray-100 dark:border-slate-700/50">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Sin categorías</h3>
                                        <p class="text-gray-500 dark:text-slate-400 text-sm">Aún no hay categorías registradas.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 animate-fade-in-up" style="animation-delay: 200ms;">
            <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 lg:sticky lg:top-24">
                
                <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-6 flex items-center gap-2 border-b border-gray-100 dark:border-slate-700/50 pb-4">
                    <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Nueva Categoría
                </h2>

                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-1.5">Nombre *</label>
                        <input type="text" name="name" required value="{{ old('name') }}" placeholder="Ej: Tecnología" 
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-1.5">Descripción</label>
                        <textarea name="description" rows="3" placeholder="Descripción opcional..." 
                                  class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all resize-none">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-1.5">Color Distintivo</label>
                        <input type="color" name="color" value="{{ old('color', '#3B82F6') }}" 
                               class="h-11 w-full rounded-xl cursor-pointer bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 p-1">
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 mt-4 rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.5 19.25V5.5A2.5 2.5 0 0 0 15 3H6a2.5 2.5 0 0 0-2.5 2.5v13.5A2.5 2.5 0 0 0 6 21h9.5a2.5 2.5 0 0 0 2.5-2.5Z" /></svg>
                        Guardar Categoría
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
