@extends('layouts.auth')
@section('title', 'Registro Exitoso')

@section('content')
<div style="text-align:center;padding:20px 0" class="anim-scale">
    {{-- Ícono animado --}}
    <div style="width:90px;height:90px;background:linear-gradient(135deg,var(--accent),var(--royal-400));
                border-radius:24px;display:flex;align-items:center;justify-content:center;
                font-size:44px;margin:0 auto 22px;
                box-shadow:var(--shadow-glow);
                animation:bounce-in .6s cubic-bezier(.4,0,.2,1)">
        🎉
    </div>

    <h1 class="font-display" style="font-size:26px;font-weight:800;color:var(--text-primary);margin-bottom:10px">
        ¡Cuenta Creada!
    </h1>

    <p style="color:var(--text-secondary);font-size:15px;margin-bottom:6px">
        Bienvenido a <strong>INCES LMS</strong>, <strong>{{ $name }}</strong> 🎓
    </p>
    <p style="color:var(--text-muted);font-size:13px;margin-bottom:30px">
        Tu cuenta ha sido registrada exitosamente como estudiante.
    </p>

    {{-- Resumen de cuenta --}}
    <div style="background:var(--bg-subtle);border:1px solid var(--border-light);border-radius:16px;
                padding:20px;margin-bottom:28px;text-align:left">
        <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;
                    color:var(--text-muted);margin-bottom:14px">Tus datos de acceso</div>
        <div style="display:flex;flex-direction:column;gap:10px">
            <div style="display:flex;align-items:center;gap:10px;font-size:14px">
                <span>👤</span>
                <span style="color:var(--text-secondary)">Nombre:</span>
                <strong style="color:var(--text-primary)">{{ $name }}</strong>
            </div>
            <div style="display:flex;align-items:center;gap:10px;font-size:14px">
                <span>✉️</span>
                <span style="color:var(--text-secondary)">Email:</span>
                <strong style="color:var(--text-primary)">{{ $email }}</strong>
            </div>
            <div style="display:flex;align-items:center;gap:10px;font-size:14px">
                <span>🎓</span>
                <span style="color:var(--text-secondary)">Rol:</span>
                <span class="badge badge-blue">Estudiante</span>
            </div>
        </div>
    </div>

    {{-- Acciones --}}
    <div style="display:flex;flex-direction:column;gap:12px">
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg" style="justify-content:center">
            🚀 Ir al Login e Ingresar
        </a>
        <p style="font-size:12px;color:var(--text-muted)">
            Usa tu correo y la contraseña que acabas de crear
        </p>
    </div>
</div>

<style>
@keyframes bounce-in {
    0%  { transform:scale(.3); opacity:0 }
    50% { transform:scale(1.05) }
    70% { transform:scale(.9) }
    100%{ transform:scale(1); opacity:1 }
}
</style>
@endsection
