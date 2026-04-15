<!DOCTYPE html>
<html lang="es" 
      x-data="{ 
          darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          sidebarOpen: false 
      }" 
      x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" 
      :class="{ 'dark': darkMode }" 
      class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'System Inces'); ?> — INCES LMS</title>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="h-full bg-gray-50 dark:bg-[#0b1120] text-gray-800 dark:text-slate-200 transition-colors duration-300 flex overflow-hidden">

    <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div x-show="sidebarOpen" @click="sidebarOpen=false"
         class="fixed inset-0 bg-gray-900/50 dark:bg-black/60 z-30 lg:hidden backdrop-blur-sm"
         x-transition.opacity.duration.300ms style="display: none;">
    </div>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <?php echo $__env->make('components.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="flex-1 overflow-y-auto p-4 md:p-8 relative">
            
            <div class="mb-6 space-y-4">
                <?php if(session('success')): ?>
                <div x-data="{show:true}" x-show="show" x-transition class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/50 text-blue-800 dark:text-blue-400 rounded-xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-medium"><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="show=false" class="hover:opacity-75"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                <div x-data="{show:true}" x-show="show" x-transition class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/50 text-red-700 dark:text-red-400 rounded-xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium"><?php echo e(session('error')); ?></span>
                    </div>
                    <button @click="show=false" class="hover:opacity-75"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <?php endif; ?>
            </div>

            <?php echo $__env->yieldContent('content'); ?>
            
        </main>
    </div>

    
    <?php if(Auth::check() && Auth::user()->role === 'student'): ?>
    <a href="<?php echo e(route('student.chatbot')); ?>" 
       class="fixed bottom-8 right-8 z-50 flex items-center justify-center w-16 h-16 bg-gradient-to-r from-red-600 to-red-800 hover:from-red-500 hover:to-red-700 dark:from-red-500 dark:to-red-700 text-white rounded-full shadow-[0_10px_25px_-5px_rgba(220,38,38,0.5)] hover:-translate-y-2 transition-all duration-300 group"
       title="Hablar con la IA del INCES">
        <span class="absolute inset-0 rounded-full bg-red-500 animate-ping opacity-25 group-hover:opacity-40 transition-opacity"></span>
        <svg class="w-8 h-8 relative z-10 group-hover:scale-110 transition-transform duration-300 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            <circle cx="9" cy="9" r="1" fill="currentColor"></circle>
            <circle cx="15" cy="9" r="1" fill="currentColor"></circle>
        </svg>
        <span class="absolute right-20 bg-blue-900 dark:bg-slate-800 text-white text-xs font-bold px-3 py-2 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none whitespace-nowrap shadow-lg border border-blue-800/50">
            Asistente INCES
        </span>
    </a>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /home/inces/Escritorio/Proyecto-Inces/resources/views/layouts/app.blade.php ENDPATH**/ ?>