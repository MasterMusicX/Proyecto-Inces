@extends('layouts.app')
@section('title', 'Gestión de Usuarios')

@section('content')
<div x-data="{ openUserDrawer: false }" class="max-w-7xl mx-auto p-6 md:p-8">
    
    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Usuarios del Sistema</h1>
        
        <button @click="openUserDrawer = true" class="bg-red-500 hover:bg-red-600 text-white-950 font-bold py-2.5 px-6 rounded-xl flex items-center transition-all shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Usuario
        </button>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-2xl p-6 mb-6 shadow-sm border border-gray-200 dark:border-transparent transition-colors">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Filtrar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o email..." 
                        class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-transparent rounded-xl pl-11 pr-4 py-3 text-gray-900 dark:text-slate-200 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-400 focus:outline-none transition-shadow">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Rol</label>
                <select name="role" onchange="this.form.submit()" class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-transparent rounded-xl px-4 py-3 text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-400 focus:outline-none transition-shadow cursor-pointer appearance-none">
                    <option value="">Todos los roles</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="instructor" {{ request('role') === 'instructor' ? 'selected' : '' }}>Instructor</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Estudiante</option>
                </select>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-2xl overflow-hidden shadow-sm border border-gray-200 dark:border-slate-700/50 transition-colors">
    
    <div class="overflow-x-auto w-full custom-scrollbar">
        
        <table class="w-full text-left text-sm text-gray-600 dark:text-slate-300 whitespace-nowrap">
            <thead class="bg-gray-50 dark:bg-[#0f172a]/50 text-gray-500 dark:text-slate-400 border-b border-gray-200 dark:border-slate-700/50">
                <tr>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs">Usuario</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs">Rol</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs">Estado</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs">Último Acceso</th>
                    <th scope="col" class="px-6 py-5 font-bold tracking-wide uppercase text-xs text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-500/20 text-blue-800 dark:text-blue-400 flex items-center justify-center font-black shadow-inner border border-blue-200 dark:border-blue-800/50">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 dark:text-white text-base">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-slate-400">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 capitalize font-medium">{{ $user->role }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg border {{ $user->is_active ? 'bg-blue-50 dark:bg-blue-500/10 border-blue-200 dark:border-blue-800/50 text-blue-600 dark:text-blue-400' : 'bg-red-50 dark:bg-red-500/10 border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400' }}">
                            {{ $user->is_active ? 'ACTIVO' : 'INACTIVO' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs font-medium text-gray-500 dark:text-slate-400">
                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.users.edit', $user) }}" class="flex items-center px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 text-xs font-bold text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 transition-all shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Editar
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400 font-medium">No hay usuarios registrados aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700/50 bg-gray-50/50 dark:bg-[#0f172a]/30">
        {{ $users->links() }}
    </div>
</div>


    <div x-show="openUserDrawer" 
         x-transition.opacity.duration.300ms
         @click="openUserDrawer = false" 
         class="fixed inset-0 bg-gray-900/40 dark:bg-black/60 z-40 backdrop-blur-sm" style="display: none;">
    </div>

    <div x-show="openUserDrawer"
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 w-full max-w-md bg-white dark:bg-[#0f172a] shadow-2xl z-50 flex flex-col border-l border-gray-200 dark:border-slate-700" style="display: none;">
        
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-slate-800">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Registrar Nuevo Usuario</h2>
            <button @click="openUserDrawer = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors focus:outline-none rounded-lg p-1 hover:bg-gray-100 dark:hover:bg-slate-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Nombre Completo</label>
                    <input type="text" name="name" required placeholder="Ej: José Davalillo"
                        class="w-full bg-gray-50 dark:bg-[#1e293b] border border-gray-300 dark:border-slate-600 rounded-xl px-4 py-3 text-gray-900 dark:text-slate-200 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-500 transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Correo Electrónico</label>
                    <input type="email" name="email" required placeholder="correo@ejemplo.com"
                        class="w-full bg-gray-50 dark:bg-[#1e293b] border border-gray-300 dark:border-slate-600 rounded-xl px-4 py-3 text-gray-900 dark:text-slate-200 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-500 transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Rol del Sistema</label>
                    <select name="role" required class="w-full bg-gray-50 dark:bg-[#1e293b] border border-gray-300 dark:border-slate-600 rounded-xl px-4 py-3 text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 transition-colors cursor-pointer">
                        <option value="" disabled selected>Selecciona un rol...</option>
                        <option value="admin">Administrador</option>
                        <option value="instructor">Instructor</option>
                        <option value="student">Estudiante</option>
                    </select>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white-950 font-bold py-3.5 px-4 rounded-xl shadow-lg transition-all flex justify-center items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection