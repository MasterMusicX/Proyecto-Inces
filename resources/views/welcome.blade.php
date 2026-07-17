<!DOCTYPE html>
<html lang="es" class="scroll-smooth"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IncesCampus | Plataforma Educativa</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo app.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo app.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/Logo app.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-[#0b1120] text-gray-800 dark:text-slate-200 transition-colors duration-300 antialiased" x-data="{ mobileMenuOpen: false }">

    <header class="fixed w-full top-0 z-50 bg-white/90 dark:bg-[#0b1120]/90 backdrop-blur-md border-b border-gray-200 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Logo INCES.png') }}" alt="Logo INCES Campus" class="h-10 w-auto">
            </div>
            
            <nav class="hidden md:flex items-center gap-8 font-medium text-sm">
                <a href="#beneficios" class="text-gray-600 dark:text-slate-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">Beneficios</a>
                <a href="#nosotros" class="text-gray-600 dark:text-slate-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">Sobre el INCES</a>
                
                <button onclick="toggleTheme()" class="p-2.5 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors focus:outline-none" aria-label="Cambiar tema">
                    <svg class="hidden dark:block w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg class="block dark:hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>
                
                @auth
                    @php
                        $dashboardRoute = auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'instructor' ? route('instructor.dashboard') : route('student.dashboard'));
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="px-6 py-2.5 bg-blue-800 hover:bg-blue-900 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-800/30 transition-all hover:-translate-y-0.5">Ir a mi Panel</a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-blue-800 hover:bg-blue-900 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-800/30 transition-all hover:-translate-y-0.5">Ingresar al Sistema</a>
                @endauth
            </nav>

            <div class="flex items-center gap-2 md:hidden">
                <button onclick="toggleTheme()" class="p-2.5 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2.5 rounded-xl text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
    </header>

    <section class="pt-32 pb-20 md:pt-40 md:pb-28 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto flex flex-col items-center text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 font-bold text-xs uppercase tracking-widest mb-6 border border-red-100 dark:border-red-900/50">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
            </span>
            Plataforma 100% Activa
        </div>
        <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-6 max-w-4xl leading-tight">
            La nueva era de la formación <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-800 to-blue-500 dark:from-blue-400 dark:to-blue-200">técnica y profesional</span>
        </h1>
        <p class="text-lg md:text-xl text-gray-600 dark:text-slate-400 mb-10 max-w-2xl">
            IncesCampus es el entorno virtual de aprendizaje diseñado específicamente para potenciar las habilidades del sector construcción y áreas afines.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center w-full">
            <a href="{{ route('login') }}" class="px-8 py-4 bg-red-600 hover:bg-red-700 dark:bg-red-500 text-white font-bold rounded-xl shadow-xl shadow-red-600/30 transition-all hover:-translate-y-1 text-lg">Comenzar a aprender</a>
            <a href="#beneficios" class="px-8 py-4 bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-200 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-lg">Conocer más</a>
        </div>
    </section>

    <section id="beneficios" class="py-20 bg-gray-50 dark:bg-[#0b1120] border-y border-gray-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-blue-900 dark:text-blue-400 mb-4">¿Por qué usar IncesCampus?</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-[#1e293b] p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 hover:-translate-y-1 transition-all border-b-4 border-b-blue-600">
                    <div class="w-14 h-14 bg-blue-50 dark:bg-blue-500/20 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Acceso 24/7</h3>
                    <p class="text-gray-600 dark:text-slate-400">Estudia desde tu computadora o celular cuando quieras.</p>
                </div>
                <div class="bg-white dark:bg-[#1e293b] p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 hover:-translate-y-1 transition-all border-b-4 border-b-red-600">
                    <div class="w-14 h-14 bg-red-50 dark:bg-red-500/20 text-red-700 dark:text-red-400 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.42 15.17L17.25 2.25A2.65 2.65 0 0012.75 3L5.25 9.06a2.25 2.25 0 00.18 3.14l4.3 3.44a2.25 2.25 0 001.69.53z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Aulas Virtuales</h3>
                    <p class="text-gray-600 dark:text-slate-400">Interacción directa con instructores y contenido práctico.</p>
                </div>
                <div class="bg-white dark:bg-[#1e293b] p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 hover:-translate-y-1 transition-all border-b-4 border-b-blue-600">
                    <div class="w-14 h-14 bg-blue-50 dark:bg-blue-500/20 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Certificación</h3>
                    <p class="text-gray-600 dark:text-slate-400">Obtén respaldo avalado por la institución.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="nosotros" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-6">Formando a la clase trabajadora</h2>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 shrink-0 bg-blue-50 dark:bg-blue-500/20 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center text-xl font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.073 6.923-3 9.423M12 11c0 3.517 1.073 6.923 3 9.423M12 11V3m0 8h9m-9 0H3"/></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">Misión</h4>
                            <p class="text-gray-600 dark:text-slate-400 mt-1">Formación integral y continua de los trabajadores para el desarrollo productivo.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 shrink-0 bg-blue-50 dark:bg-blue-500/20 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center text-xl font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">Visión</h4>
                            <p class="text-gray-600 dark:text-slate-400 mt-1">Ser vanguardia en la educación técnica, reconocida por su excelencia y pertinencia social.</p>
                        </div>
                    </div>
                </div>
            </div>
            <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=2070&auto=format&fit=crop" alt="Práctica" class="rounded-3xl shadow-2xl object-cover h-[500px] w-full">
        </div>
    </section>

    <footer class="bg-blue-950 dark:bg-[#0b1120] border-t border-gray-200 dark:border-slate-800 py-12 text-center transition-colors">
        <p class="text-blue-300 dark:text-slate-500 text-sm">&copy; {{ date('Y') }} INCES. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
