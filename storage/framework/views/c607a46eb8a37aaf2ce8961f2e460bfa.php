<?php
  $role = Auth::user()->role;
  $current = request()->route()->getName() ?? '';
?>

<aside 
    :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 lg:w-20'" 
    class="fixed lg:static inset-y-0 left-0 z-40 bg-white dark:bg-[#0f172a] border-r border-gray-200 dark:border-slate-700/50 flex flex-col transition-all duration-300 h-full shadow-2xl lg:shadow-sm">
    
    
    <a href="<?php echo e($role === 'admin' ? route('admin.dashboard') : ($role === 'instructor' ? route('instructor.dashboard') : route('student.dashboard'))); ?>"
       class="h-16 flex items-center px-4 border-b border-gray-100 dark:border-slate-700/50 overflow-hidden hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
        
        <div class="text-2xl flex-shrink-0 w-10 h-10 flex items-center justify-center bg-red-50 dark:bg-red-500/20 text-red-600 rounded-xl shadow-inner">🎓</div>
        
        <div x-show="sidebarOpen" x-transition.opacity class="ml-3 flex items-center whitespace-nowrap">
            <img src="<?php echo e(asset('images/Logo INCES.png')); ?>" alt="Logo INCES Campus" class="h-8 w-auto">
        </div>
    </a>

    
    <nav class="flex-1 overflow-y-auto py-6 space-y-1 custom-scrollbar">

        <?php if($role === 'admin'): ?>
            <div x-show="sidebarOpen" x-transition.opacity class="px-6 text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2">Principal</div>
            <?php echo sidebarLink(route('admin.dashboard'),    '📊', 'Dashboard',         'admin.dashboard',    $current); ?>

            <?php echo sidebarLink(route('admin.statistics'),   '📈', 'Estadísticas',      'admin.statistics',   $current); ?>


            <div x-show="sidebarOpen" x-transition.opacity class="px-6 text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2 mt-6">Gestión</div>
            <?php echo sidebarLink(route('admin.users.index'),      '👥', 'Usuarios',         'admin.users.*',      $current); ?>

            <?php echo sidebarLink(route('admin.courses.index'),    '📚', 'Cursos',           'admin.courses.*',    $current); ?>

            <?php echo sidebarLink(route('admin.categories.index'), '🏷️', 'Categorías',       'admin.categories.*', $current); ?>


            <div x-show="sidebarOpen" x-transition.opacity class="px-6 text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2 mt-6">IA</div>
            <?php echo sidebarLink(route('admin.knowledge-base.index'), '🧠', 'Base de Datos', 'admin.knowledge-base.*', $current); ?>


        <?php elseif($role === 'instructor'): ?>
            <div x-show="sidebarOpen" x-transition.opacity class="px-6 text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2">Mi Panel</div>
            <?php echo sidebarLink(route('instructor.dashboard'),      '📊', 'Dashboard',    'instructor.dashboard', $current); ?>

            <?php echo sidebarLink(route('instructor.courses.index'),  '📚', 'Mis Cursos',   'instructor.courses.*', $current); ?>


        <?php else: ?>
            <div x-show="sidebarOpen" x-transition.opacity class="px-6 text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2">Mi Aprendizaje</div>
            <?php echo sidebarLink(route('student.dashboard'),       '🏠', 'Mi Panel',            'student.dashboard',      $current); ?>

            <?php echo sidebarLink(route('student.courses.catalog'), '📚', 'Catálogo',            'student.courses.*',      $current); ?>

            <?php echo sidebarLink(route('student.search'),          '🔍', 'Búsqueda IA',         'student.search',         $current); ?>

        <?php endif; ?>

    </nav>

    
    <div class="p-4 border-t border-gray-100 dark:border-slate-700/50 bg-gray-50/50 dark:bg-[#1e293b]/20">
        
        <div class="flex items-center p-2 rounded-xl bg-white dark:bg-[#1e293b] shadow-sm border border-gray-100 dark:border-slate-700/30 mb-3 overflow-hidden">
            <img src="<?php echo e(Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name)); ?>&background=1e40af&color=fff" 
                 alt="Avatar" class="w-9 h-9 rounded-lg object-cover flex-shrink-0 border border-gray-200 dark:border-slate-600">
            
            <div x-show="sidebarOpen" x-transition.opacity class="ml-3 min-w-0">
                <div class="text-sm font-bold text-gray-900 dark:text-white truncate">
                    <?php echo e(Auth::user()->name); ?>

                </div>
                <div class="text-[10px] text-blue-600 dark:text-blue-400 font-bold uppercase tracking-tight">
                    <?php echo e(Auth::user()->role); ?>

                </div>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-all group" title="Cerrar Sesión">
                <span class="text-xl mr-3 group-hover:scale-110 transition-transform">🚪</span>
                <span x-show="sidebarOpen" x-transition.opacity>Cerrar Sesión</span>
            </button>
        </form>
    </div>
</aside><?php /**PATH /home/inces/Escritorio/Proyecto Inces/resources/views/components/sidebar.blade.php ENDPATH**/ ?>