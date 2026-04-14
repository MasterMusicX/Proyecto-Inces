@extends('layouts.app')
@section('title', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-display font-bold text-gray-900 dark:text-white mb-6">Mi Perfil</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Stats sidebar -->
        <div class="space-y-4">
            <!-- Avatar Card -->
            <div class="card p-6 text-center">
                <div class="relative inline-block mb-4">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                        class="w-24 h-24 rounded-full border-4 border-brand-100 shadow-lg mx-auto">
                    <span class="absolute bottom-0 right-0 w-7 h-7 bg-green-400 rounded-full border-2 border-white"></span>
                </div>
                <h2 class="font-display font-bold text-xl text-gray-900 dark:text-white">{{ $user->name }}</h2>
                <p class="text-brand-500 font-medium text-sm">Estudiante INCES</p>
                <p class="text-gray-400 text-xs mt-1">Miembro desde {{ $user->created_at->format('M Y') }}</p>
            </div>

            <!-- Stats -->
            <div class="card p-5">
                <h3 class="font-bold text-gray-700 dark:text-gray-300 text-sm mb-4 uppercase tracking-wide">Mis Estadísticas</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">📚 Cursos inscritos</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $stats['enrolled'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">✅ Completados</span>
                        <span class="font-bold text-green-600">{{ $stats['completed'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">🤖 Consultas IA</span>
                        <span class="font-bold text-purple-600">{{ $stats['ai_queries'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms column -->
        <div class="lg:col-span-2 space-y-5">
            <!-- Edit Profile -->
            <div class="card p-6">
                <h2 class="font-display font-bold text-lg text-gray-900 dark:text-white mb-5">✏️ Editar Información</h2>
                <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @if(session('success'))
                        <div class="p-3 bg-green-50 border border-green-200 rounded-xl text-green-600 text-sm">✅ {{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">{{ $errors->first() }}</div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nombre Completo *</label>
                            <input name="name" value="{{ old('name', $user->name) }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Teléfono</label>
                            <input name="phone" value="{{ old('phone', $user->phone) }}" class="form-input" placeholder="0424-0000000">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled
                            class="form-input opacity-60 cursor-not-allowed bg-gray-50 dark:bg-gray-600">
                        <p class="text-xs text-gray-400 mt-1">El email no puede ser modificado.</p>
                    </div>
                    <div>
                        <label class="form-label">Biografía</label>
                        <textarea name="bio" rows="3" class="form-input"
                            placeholder="Cuéntanos un poco sobre ti...">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">Foto de Perfil</label>
                        <input type="file" name="avatar" accept="image/*" class="form-input">
                    </div>
                    <button type="submit" class="btn-primary">💾 Guardar Cambios</button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="card p-6">
                <h2 class="font-display font-bold text-lg text-gray-900 dark:text-white mb-5">🔒 Cambiar Contraseña</h2>
                <form method="POST" action="{{ route('student.profile.password') }}" class="space-y-4">
                    @csrf
                    @if($errors->has('current_password'))
                        <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">{{ $errors->first('current_password') }}</div>
                    @endif
                    <div>
                        <label class="form-label">Contraseña Actual *</label>
                        <input type="password" name="current_password" required class="form-input">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nueva Contraseña *</label>
                            <input type="password" name="password" required minlength="8" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Confirmar *</label>
                            <input type="password" name="password_confirmation" required class="form-input">
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">🔒 Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
