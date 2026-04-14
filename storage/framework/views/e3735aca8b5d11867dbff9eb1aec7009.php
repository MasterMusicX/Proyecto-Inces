<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | IncesCampus</title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        @keyframes slideDownFade {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-alert { animation: slideDownFade 0.4s ease-out; }

        /* Loader Styles adaptados a INCES (Rojo) */
        #topLoader {
            position: fixed;
            top: 0;
            left: 0;
            height: 4px;
            width: 0;
            background-color: #dc2626; /* red-600 */
            z-index: 10000;
            transition: width .10s ease-out;
        }
        #topLoader.hide { opacity: 0; transition: opacity .4s ease-out; }

        .animate-pulse-slow { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    </style>

    <script>
        // Comprueba el localStorage para evitar parpadeos
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="bg-gray-50 dark:bg-[#0f172a] transition-colors duration-300 overflow-auto md:overflow-hidden min-h-screen relative flex">

    <a href="<?php echo e(url('/')); ?>" class="fixed top-6 left-6 z-50 flex items-center gap-2 px-4 py-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-gray-200 dark:border-slate-700 rounded-full text-sm font-medium text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all shadow-sm group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Volver
    </a>

    <div id="mainLoader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-50 dark:bg-[#0f172a] transition-opacity duration-500">
        <div class="relative flex items-center justify-center">
            <div class="w-24 h-24 border-4 border-blue-100 dark:border-slate-800 border-t-blue-800 rounded-full animate-spin"></div>
            <div class="absolute flex items-center justify-center animate-pulse-slow bg-white rounded-full p-2 shadow-lg w-16 h-16">
                <img src="<?php echo e(asset('images/Logo INCES.png')); ?>" class="w-full h-auto object-contain" alt="Cargando...">
            </div>
        </div>
    </div>

    <div id="topLoader"></div>

    <?php if($errors->any() || session('success')): ?>
        <div id="alerta" class="fixed top-4 right-4 z-50 animate-alert">
            <div class="bg-white dark:bg-slate-800 text-gray-900 dark:text-white border <?php echo e($errors->any() ? 'border-red-500' : 'border-blue-500'); ?> rounded-xl shadow-2xl px-5 py-4 w-80 flex items-start space-x-3">
                <span class="<?php echo e($errors->any() ? 'text-red-500' : 'text-blue-500'); ?> text-xl">
                    <?php echo e($errors->any() ? '❌' : '✅'); ?>

                </span>
                <div class="flex-1">
                    <h3 class="font-bold text-sm"><?php echo e($errors->any() ? 'Error de autenticación' : '¡Excelente!'); ?></h3>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1"><?php echo e($errors->any() ? $errors->first() : session('success')); ?></p>
                </div>
                <button onclick="document.getElementById('alerta').remove();" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">✕</button>
            </div>
        </div>
        <script>setTimeout(() => { const a = document.getElementById('alerta'); if (a) a.remove(); }, 5000);</script>
    <?php endif; ?>

    <div class="w-full grid grid-cols-1 md:grid-cols-2 min-h-screen">
        
        <div class="hidden md:flex flex-col justify-center items-center relative bg-blue-500 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/50 to-blue-550/50 dark:from-slate-900/90 dark:to-gray-900/90 z-10 transition-colors"></div>
            
            <img src="<?php echo e(asset('img/login-educacion.jpg')); ?>" onerror="this.src='https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=2070&auto=format&fit=crop'" class="absolute inset-0 w-full h-full object-cover z-0 mix-blend-overlay opacity-50" alt="Estudiantes INCES">
            
            <div class="relative z-20 text-center px-12">
                <div class="mb-8 flex items-center justify-center">
                    <img src="<?php echo e(asset('images/Logo INCES.png')); ?>" alt="Logo INCES Campus" class="h-24 w-auto brightness-0 invert drop-shadow-md">
                </div>
                <h2 class="text-4xl font-extrabold text-white mb-4">Formando a la <br><span class="text-red-500">Clase Trabajadora.</span></h2>
                <p class="text-lg text-blue-100 dark:text-gray-300 max-w-md mx-auto">La plataforma de formación virtual del INCES para el desarrollo profesional y técnico.</p>
            </div>
        </div>

        <div class="md:hidden h-48 relative bg-blue-950">
            <div class="absolute inset-0 bg-blue-900/80 z-10"></div>
            <img src="<?php echo e(asset('img/login-educacion.jpg')); ?>" onerror="this.src='https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=2070&auto=format&fit=crop'" class="absolute inset-0 w-full h-full object-cover z-0" alt="Estudiantes">
            <div class="relative z-20 flex items-center justify-center h-full">
                <img src="<?php echo e(asset('images/Logo INCES.png')); ?>" alt="Logo INCES Campus" class="h-16 w-auto brightness-0 invert drop-shadow-md">
            </div>
        </div>

        <div class="flex flex-col justify-center items-center px-8 sm:px-12 md:px-20 py-12 bg-white dark:bg-[#0f172a]">
            
            <div class="w-full max-w-sm">
                <div class="text-center mb-10">
                    <img src="<?php echo e(asset('images/Logo INCES.png')); ?>" alt="Logo INCES" class="h-14 w-auto mx-auto mb-4 md:hidden">
                    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        ¡Bienvenido de nuevo!
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
                        Ingresa tus credenciales para acceder a tus cursos.
                    </p>
                </div>

                <form action="<?php echo e(route('login')); ?>" method="POST" class="space-y-5">
                    <?php echo csrf_field(); ?>
                    
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-1">Correo Electrónico</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 dark:text-slate-500">✉️</span>
                            </div>
                            <input type="email" name="email" id="email" required autofocus
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-800 focus:border-transparent outline-none transition-all" 
                                value="<?php echo e(old('email')); ?>" placeholder="tu.correo@ejemplo.com">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-1">Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 dark:text-slate-500">🔒</span>
                            </div>
                            <input type="password" name="password" id="password" required 
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-[#1e293b] border border-gray-200 dark:border-slate-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-800 focus:border-transparent outline-none transition-all" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-blue-800 bg-gray-100 border-gray-300 rounded focus:ring-blue-800 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 transition-colors">
                            <span class="text-gray-600 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Recordarme</span>
                        </label>
                        
                        <a href="<?php echo e(route('password.request')); ?>" class="font-bold text-blue-800 dark:text-blue-400 hover:text-blue-600 transition-colors">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-red-600/30 text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-all hover:-translate-y-0.5">
                        Iniciar Sesión
                    </button>
                </form>

                <div class="mt-8 flex items-center">
                    <div class="flex-grow border-t border-gray-200 dark:border-slate-700"></div>
                    <span class="mx-4 text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-widest">IncesCampus</span>
                    <div class="flex-grow border-t border-gray-200 dark:border-slate-700"></div>
                </div>

                <p class="mt-8 text-center text-sm text-gray-600 dark:text-slate-400">
                    ¿Eres nuevo en la institución?
                    <a href="<?php echo e(route('register')); ?>" class="font-bold text-blue-800 dark:text-blue-400 hover:text-blue-600 transition-colors">Solicita tu acceso aquí</a>
                </p>
                
            </div>
        </div>
    </div>

    <script>
        window.addEventListener("load", () => {
            const bar = document.getElementById("topLoader");
            const mainLoader = document.getElementById("mainLoader");

            setTimeout(() => bar.style.width = "100%", 50);

            setTimeout(() => {
                mainLoader.classList.add("opacity-0");
                mainLoader.classList.add("pointer-events-none");
                bar.classList.add("hide");
                
                setTimeout(() => {
                    mainLoader.remove();
                    bar.remove();
                }, 500);
            }, 800);
        });
    </script>

</body>
</html><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/auth/login.blade.php ENDPATH**/ ?>