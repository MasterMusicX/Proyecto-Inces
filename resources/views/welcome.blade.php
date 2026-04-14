<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IncesCampus | Plataforma Educativa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Función para cambiar el tema con el botón
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
                
                <button onclick="toggleTheme()" class="p-2.5 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Cambiar tema">
                    <svg class="hidden dark:block w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <svg class="block dark:hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </button>
                
                @auth
                    @php
                        $dashboardRoute = auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'instructor' ? route('instructor.dashboard') : route('student.dashboard'));
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="px-6 py-2.5 bg-blue-800 hover:bg-blue-900 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-800/30 transition-all hover:-translate-y-0.5">
                        Ir a mi Panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-blue-800 hover:bg-blue-900 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-800/30 transition-all hover:-translate-y-0.5">
                        Ingresar al Sistema
                    </a>
                @endauth
            </nav>
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
            IncesCampus es el entorno virtual de aprendizaje diseñado específicamente para potenciar las habilidades del sector construcción y áreas afines, conectando instructores y aprendices sin límites geográficos.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center w-full">
            @auth
                <a href="{{ $dashboardRoute }}" class="px-8 py-4 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white font-bold rounded-xl shadow-xl shadow-red-600/30 transition-all hover:-translate-y-1 text-lg">
                    Continuar mi aprendizaje
                </a>
            @else
                <a href="{{ route('login') }}" class="px-8 py-4 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white font-bold rounded-xl shadow-xl shadow-red-600/30 transition-all hover:-translate-y-1 text-lg">
                    Comenzar a aprender
                </a>
            @endauth
            <a href="#beneficios" class="px-8 py-4 bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-200 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-lg">
                Conocer más
            </a>
        </div>
    </section>

    <section id="beneficios" class="py-20 bg-gray-50 dark:bg-[#0b1120] border-y border-gray-200 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-blue-900 dark:text-blue-400 mb-4">¿Por qué usar IncesCampus?</h2>
                <p class="text-gray-600 dark:text-slate-400 max-w-2xl mx-auto">Un sistema modular pensado para facilitar la enseñanza y el aprendizaje en oficios productivos.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-[#1e293b] p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 hover:-translate-y-1 transition-all border-b-4 border-b-blue-600">
                    <div class="w-14 h-14 bg-blue-50 dark:bg-blue-500/20 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center text-2xl mb-6">📱</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Acceso 24/7 Multiplataforma</h3>
                    <p class="text-gray-600 dark:text-slate-400 leading-relaxed">Estudia desde tu computadora, tablet o celular. El contenido de tus cursos está disponible cuando y donde lo necesites.</p>
                </div>
                <div class="bg-white dark:bg-[#1e293b] p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 hover:-translate-y-1 transition-all border-b-4 border-b-red-600">
                    <div class="w-14 h-14 bg-red-50 dark:bg-red-500/20 text-red-700 dark:text-red-400 rounded-xl flex items-center justify-center text-2xl mb-6">🛠️</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Aulas Virtuales Dinámicas</h3>
                    <p class="text-gray-600 dark:text-slate-400 leading-relaxed">Interacción directa con instructores capacitados, entrega de asignaciones prácticas y seguimiento en tiempo real de tu progreso.</p>
                </div>
                <div class="bg-white dark:bg-[#1e293b] p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 hover:-translate-y-1 transition-all border-b-4 border-b-blue-600">
                    <div class="w-14 h-14 bg-blue-50 dark:bg-blue-500/20 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center text-2xl mb-6">📜</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Certificación Técnica</h3>
                    <p class="text-gray-600 dark:text-slate-400 leading-relaxed">Al culminar satisfactoriamente los módulos, obtienes el respaldo y la certificación avalada por el Instituto.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="nosotros" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-6">Formando a la clase trabajadora</h2>
                <p class="text-lg text-gray-600 dark:text-slate-400 mb-6 leading-relaxed">
                    El Instituto Nacional de Capacitación y Educación Socialista (INCES) es el ente rector de la formación técnica profesional en el país. Nuestra misión es promover la inclusión socioproductiva a través de la educación.
                </p>
                
                <div class="space-y-6 mt-8">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 shrink-0 bg-blue-50 dark:bg-blue-500/20 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center text-xl font-bold">🎯</div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">Misión</h4>
                            <p class="text-gray-600 dark:text-slate-400 mt-1">El Inces es la institución del Estado encargada de la formación y autoformación colectiva, integral, continua y permanente de los trabajadores y trabajadoras, orientada al desarrollo de sus capacidades para la producción de bienes y prestación de servicios.
Desarrollar programas de formación y capacitación integral para el trabajo, orientados a satisfacer las necesidades del modelo productivo.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 shrink-0 bg-blue-50 dark:bg-blue-500/20 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center text-xl font-bold">👁️</div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">Visión</h4>
                            <p class="text-gray-600 dark:text-slate-400 mt-1">Ser una institución vanguardista en la educación técnica, reconocida por su excelencia académica y pertinencia social.Convertirse en una poderosa herramienta para la transformación y consolidación de una economía soberana y diversificada. Ser un referente nacional e internacional de la formación técnica profesional inclusiva y colectiva.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 shrink-0 bg-blue-50 dark:bg-blue-500/20 text-blue-800 dark:text-blue-400 rounded-xl flex items-center justify-center text-xl font-bold">⭐</div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">Valores</h4>
                            <p class="text-gray-600 dark:text-slate-400 mt-1">Ética, responsabilidad, innovación, compromiso social y excelencia educativa.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-800 to-blue-500 rounded-3xl transform rotate-3 scale-105 opacity-20 dark:opacity-40"></div>
                <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=2070&auto=format&fit=crop" alt="Estudiantes en práctica técnica" class="relative rounded-3xl shadow-2xl object-cover h-[600px] w-full border border-gray-100 dark:border-slate-800">
            </div>
        </div>
    </section>

    <footer class="bg-blue-950 dark:bg-[#0b1120] border-t border-gray-200 dark:border-slate-800 py-12 text-center transition-colors">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-center gap-3 mb-4">
              <img src="{{ asset('images/Logo INCES.png') }}" alt="Logo INCES Campus" class="h-10 w-auto">
             </div>
            <p class="text-blue-300 dark:text-slate-500 text-sm">
                &copy; {{ date('Y') }} Instituto Nacional de Capacitación y Educación Socialista. Todos los derechos reservados.
            </p>
        </div>
    </footer>

</body>
</html>