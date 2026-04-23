@extends('layouts.app')
@section('title', 'Crear Nuevo Usuario')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Nuevo Usuario</h1>
            <p class="text-gray-500 dark:text-slate-400 text-sm mt-1">Registra un nuevo integrante en la plataforma del INCES.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white transition-colors flex items-center font-medium">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden transition-colors duration-300">
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 md:p-8">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl mb-6 shadow-sm">
                    <strong class="font-bold text-sm">¡Atención! No se pudo crear el usuario:</strong>
                    <ul class="mt-1 list-disc list-inside text-xs font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Nombre Completo</label>
                    <input type="text" name="name" id="name" required placeholder="Ej: José Davalillo"
                        class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-300 dark:border-slate-600 rounded-xl px-4 py-3 text-gray-900 dark:text-slate-200 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Correo Electrónico</label>
                    <input type="email" name="email" id="email" required placeholder="correo@ejemplo.com"
                        class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-300 dark:border-slate-600 rounded-xl px-4 py-3 text-gray-900 dark:text-slate-200 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-500 transition-colors">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Contraseña Temporal</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required placeholder="••••••••"class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-300 dark:border-slate-600 rounded-xl pl-4 pr-12 py-3 text-gray-900 dark:text-slate-200 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-500 transition-colors">
                            <button type="button" onclick="togglePassword('password', 'eye-open-pass', 'eye-closed-pass')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-500 transition-colors">
                            <svg id="eye-open-pass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg id="eye-closed-pass" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                </button>
                        </div>
                </div>

                <div>
                     <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Confirmar Contraseña</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••"class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-300 dark:border-slate-600 rounded-xl pl-4 pr-12 py-3 text-gray-900 dark:text-slate-200 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-blue-500 transition-colors">
                                <button type="button" onclick="togglePassword('password_confirmation', 'eye-open-conf', 'eye-closed-conf')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-500 transition-colors">
                                <svg id="eye-open-conf" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg id="eye-closed-conf" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                    </button>
                        </div>
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Rol del Sistema</label>
                    <select name="role" id="role" required
                        class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-300 dark:border-slate-600 rounded-xl px-4 py-3 text-gray-900 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 transition-colors appearance-none cursor-pointer">
                        <option value="" disabled selected>Selecciona un rol...</option>
                        <option value="admin">Administrador</option>
                        <option value="instructor">Instructor</option>
                        <option value="student">Estudiante</option>
                    </select>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-slate-700/50 my-8">

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 font-medium text-gray-700 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 font-bold text-blue-950 bg-[#86efac] hover:bg-[#6ee7b7] rounded-xl shadow-lg transition-all flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar Usuario
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    function togglePassword(inputId, eyeOpenId, eyeClosedId) {
        const input = document.getElementById(inputId);
        const eyeOpen = document.getElementById(eyeOpenId);
        const eyeClosed = document.getElementById(eyeClosedId);

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>
@endsection
