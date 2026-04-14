<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | IncesCampus</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-[#0f172a] transition-colors duration-300 min-h-screen flex items-center justify-center p-4">

    <a href="<?php echo e(route('login')); ?>" class="absolute top-6 left-6 flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-full text-sm font-medium text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all shadow-sm">
        &larr; Volver al Login
    </a>

    <div class="w-full max-w-md bg-white dark:bg-[#1e293b] rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700/50 p-8">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-500/20 text-blue-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                🔑
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white">Recuperar Acceso</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-2">
                Ingresa el correo electrónico asociado a tu cuenta y te enviaremos un enlace para restablecer tu contraseña.
            </p>
        </div>

        <?php if(session('status')): ?>
            <div class="mb-6 p-4 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30 text-blue-700 dark:text-blue-400 text-sm font-medium text-center">
                ✅ <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 text-rose-700 dark:text-rose-400 text-sm font-medium text-center">
                ❌ <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.email')); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>
            <div>
                <label for="email" class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-1">Correo Electrónico</label>
                <input type="email" name="email" id="email" required autofocus
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-slate-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 outline-none transition-all" 
                    placeholder="tu.correo@ejemplo.com">
            </div>

            <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-blue-500 hover:bg-blue-600 transition-all hover:-translate-y-0.5">
                Enviar enlace de recuperación
            </button>
        </form>

    </div>
</body>
</html><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>