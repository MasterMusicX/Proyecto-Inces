<!DOCTYPE html>
<html lang="es" x-data="{ darkMode: localStorage.getItem('darkMode')==='true' }"
      :class="{ dark: darkMode }" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Acceso') — INCES LMS</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .auth-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .auth-left {
            background: linear-gradient(145deg, #1e2d87 0%, #2445e7 40%, #608af8 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 60px; position: relative; overflow: hidden;
        }
        .dark .auth-left {
            background: linear-gradient(145deg, #061220 0%, #0a2518 40%, #065f46 100%);
        }
        .auth-left::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='40' cy='40' r='3'/%3E%3C/g%3E%3C/svg%3E");
        }
        .auth-left::after {
            content: '';
            position: absolute; top: -150px; right: -150px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse-bg 4s ease-in-out infinite;
        }
        @keyframes pulse-bg {
            0%,100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .auth-right {
            background: var(--bg-base);
            display: flex; align-items: center; justify-content: center;
            padding: 40px;
        }
        .auth-card {
            width: 100%; max-width: 440px;
            background: var(--bg-surface);
            border: 1px solid var(--border-light);
            border-radius: 24px;
            padding: 40px;
            box-shadow: var(--shadow-lg);
            animation: scale-in .4s cubic-bezier(.4,0,.2,1);
        }
        @keyframes scale-in {
            from { opacity:0; transform: scale(.96) translateY(10px); }
            to   { opacity:1; transform: scale(1)   translateY(0); }
        }
        .auth-features { position: relative; z-index: 1; text-align: center; }
        .auth-feature-item {
            display: flex; align-items: center; gap: 14px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 14px; padding: 14px 18px;
            margin-bottom: 12px; text-align: left;
            backdrop-filter: blur(10px);
            animation: slide-left .5s ease forwards;
            opacity: 0;
        }
        @keyframes slide-left {
            from { opacity:0; transform:translateX(-20px); }
            to   { opacity:1; transform:translateX(0); }
        }
        @media(max-width:768px) {
            .auth-page { grid-template-columns: 1fr; }
            .auth-left { display: none; }
        }
    </style>
</head>
<body>
<div class="auth-page">
    {{-- Left panel decorativo --}}
    <div class="auth-left">
        <div class="auth-features">
            <div style="font-size:64px;margin-bottom:16px;animation:bounce-in .6s ease">🎓</div>
            <h1 style="font-family:'Outfit',sans-serif;font-size:32px;font-weight:800;color:white;margin-bottom:8px">
                INCES LMS
            </h1>
            <p style="color:rgba(255,255,255,.7);font-size:15px;margin-bottom:36px">
                Plataforma de Aprendizaje con IA Conversacional
            </p>

            <div class="auth-feature-item" style="animation-delay:.1s">
                <div style="font-size:28px">📚</div>
                <div>
                    <div style="color:white;font-weight:600;font-size:14px">Cursos Certificados</div>
                    <div style="color:rgba(255,255,255,.6);font-size:12px">Formación técnica oficial del INCES</div>
                </div>
            </div>
            <div class="auth-feature-item" style="animation-delay:.2s">
                <div style="font-size:28px">🤖</div>
                <div>
                    <div style="color:white;font-weight:600;font-size:14px">Asistente Virtual IA</div>
                    <div style="color:rgba(255,255,255,.6);font-size:12px">Powered by Google Gemini Pro</div>
                </div>
            </div>
            <div class="auth-feature-item" style="animation-delay:.3s">
                <div style="font-size:28px">📄</div>
                <div>
                    <div style="color:white;font-weight:600;font-size:14px">Análisis de Documentos</div>
                    <div style="color:rgba(255,255,255,.6);font-size:12px">IA extrae resúmenes y conceptos clave</div>
                </div>
            </div>
            <div class="auth-feature-item" style="animation-delay:.4s">
                <div style="font-size:28px">🏆</div>
                <div>
                    <div style="color:white;font-weight:600;font-size:14px">Certificados Oficiales</div>
                    <div style="color:rgba(255,255,255,.6);font-size:12px">Reconocidos a nivel nacional</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right panel con el formulario --}}
    <div class="auth-right">
        <div class="auth-card">
            @yield('content')
        </div>
    </div>
</div>

@stack('scripts')
</body>
</html>
