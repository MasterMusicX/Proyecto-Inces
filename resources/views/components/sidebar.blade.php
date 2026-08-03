<?php
  $role = Auth::user()->role;
  $current = request()->route()->getName() ?? '';

  // 🔥 LÓGICA CORREGIDA: Colores más oscuros y definidos para el Modo Claro
  if (!function_exists('sidebarLink')) {
      function sidebarLink($url, $icon, $label, $routePattern, $current) {
          $isActive = request()->routeIs($routePattern);
          
          // Si está activo: Fondo azul clarito y texto azul rey (Modo Claro) / Fondo oscuro y texto blanco (Modo Oscuro)
          $activeClasses = $isActive 
              ? 'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' 
              : 'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium';
          
          return '
          <a href="'.$url.'" class="flex items-center px-4 py-3 mb-1 rounded-xl transition-all group '.$activeClasses.'" title="'.$label.'">
              <span class="text-xl flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform">'.$icon.'</span>
              <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">'.$label.'</span>
          </a>';
      }
  }
?>

<aside 
    :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 lg:w-20'" 
    class="fixed lg:static inset-y-0 left-0 z-40 bg-white dark:bg-[#0f172a] border-r border-gray-200 dark:border-slate-700/50 flex flex-col transition-all duration-300 h-full shadow-2xl lg:shadow-sm">
    
    @php
        $role = Auth::user()->role;
    @endphp

    {{-- Brand (Logo) --}}
    <a href="{{ $role === 'admin' ? route('admin.dashboard') : ($role === 'instructor' ? route('instructor.dashboard') : route('student.dashboard')) }}"
       class="h-16 flex items-center px-4 border-b border-gray-100 dark:border-slate-700/50 overflow-hidden hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
        
        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-red-50 dark:bg-red-500/20 text-red-600 rounded-xl shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
        </div>
        
        <div x-show="sidebarOpen" x-transition.opacity class="ml-3 flex items-center whitespace-nowrap">
            <img src="{{ asset('images/Logo INCES.png') }}" alt="Logo INCES Campus" class="h-8 w-auto drop-shadow-sm">
        </div>
    </a>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-6 space-y-1 custom-scrollbar">

        @if($role === 'admin')
            <div x-show="sidebarOpen" x-transition.opacity class="px-6 text-[11px] font-bold text-gray-500 dark:text-slate-500 uppercase tracking-widest mb-2">Principal</div>
            
            <a href="{{ route('admin.dashboard') }}" title="Dashboard"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('admin.dashboard'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('admin.dashboard')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Dashboard</span>
            </a>

            <a href="{{ route('admin.statistics') }}" title="Estadísticas"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('admin.statistics'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('admin.statistics')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Estadísticas</span>
            </a>

            <div x-show="sidebarOpen" x-transition.opacity class="px-6 text-[11px] font-bold text-gray-500 dark:text-slate-500 uppercase tracking-widest mb-2 mt-6">Gestión</div>
            
            <a href="{{ route('admin.users.index') }}" title="Usuarios"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('admin.users.*'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('admin.users.*')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Usuarios</span>
            </a>

            <a href="{{ route('admin.courses.index') }}" title="Cursos"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('admin.courses.index') || request()->routeIs('admin.courses.create') || request()->routeIs('admin.courses.edit'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('admin.courses.index')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Cursos</span>
            </a>

            <a href="{{ route('admin.courses.force-enroll') }}" title="Inscripción Expresa"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('admin.courses.force-enroll*'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('admin.courses.force-enroll*')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" /></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Inscripción Expresa</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" title="Categorías"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('admin.categories.*'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('admin.categories.*')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Categorías</span>
            </a>

            <div x-show="sidebarOpen" x-transition.opacity class="px-6 text-[11px] font-bold text-gray-500 dark:text-slate-500 uppercase tracking-widest mb-2 mt-6">IA</div>
            
            <a href="{{ route('admin.knowledge-base.index') }}" title="Base de Datos"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('admin.knowledge-base.*'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('admin.knowledge-base.*')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Base de Datos</span>
            </a>

        @elseif($role === 'instructor')
            <div x-show="sidebarOpen" x-transition.opacity class="px-6 text-[11px] font-bold text-gray-500 dark:text-slate-500 uppercase tracking-widest mb-2">Mi Panel</div>
            
            <a href="{{ route('instructor.dashboard') }}" title="Dashboard"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('instructor.dashboard'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('instructor.dashboard')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Dashboard</span>
            </a>

            <a href="{{ route('instructor.courses.index') }}" title="Mis Cursos"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('instructor.courses.*'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('instructor.courses.*')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Mis Cursos</span>
            </a>

            <a href="{{ route('instructor.submissions.index') }}" title="Revisiones Tareas/Récipes"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('instructor.submissions.*'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('instructor.submissions.*')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Tareas / Justificativos</span>
            </a>

        @else
            <div x-show="sidebarOpen" x-transition.opacity class="px-6 text-[11px] font-bold text-gray-500 dark:text-slate-500 uppercase tracking-widest mb-2">Mi Aprendizaje</div>
            
            <a href="{{ route('student.dashboard') }}" title="Mi Panel"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('student.dashboard'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('student.dashboard')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Mi Panel</span>
            </a>

            <a href="{{ route('student.courses.catalog') }}" title="Catálogo"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('student.courses.*'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('student.courses.*')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Catálogo</span>
            </a>

            <a href="{{ route('student.submissions.index') }}" title="Entregables y Récipes"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('student.submissions.*'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('student.submissions.*')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Mis Tareas / Justificativos</span>
            </a>

            <a href="{{ route('student.search') }}" title="Búsqueda IA"
               @class([
                   'flex items-center px-4 py-3 mb-1 rounded-xl transition-all group',
                   'bg-blue-50 dark:bg-slate-800 text-blue-800 dark:text-white font-extrabold shadow-sm border border-blue-100 dark:border-transparent' => request()->routeIs('student.search'),
                   'text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 hover:text-blue-700 dark:hover:text-blue-400 font-medium' => !request()->routeIs('student.search')
               ])>
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 whitespace-nowrap">Búsqueda IA</span>
            </a>
        @endif

    </nav>

    {{-- User + Logout --}}
    <div class="shrink-0 p-4 border-t border-gray-100 dark:border-slate-700/50 bg-gray-50/50 dark:bg-[#1e293b]/20 mt-auto">
        
        <div class="flex items-center rounded-xl bg-white dark:bg-[#1e293b] shadow-sm border border-gray-200 dark:border-slate-700/30 mb-3 transition-all duration-300 overflow-hidden"
             :class="sidebarOpen ? 'p-2' : 'p-1 justify-center'">
            
            @php
                $avatar = Auth::user()->avatar;
                $avatarUrl = $avatar 
                    ? (str_starts_with($avatar, 'http') ? $avatar : asset('storage/' . $avatar))
                    : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=ce202a&color=fff&bold=true';
            @endphp
            
            <img src="{{ $avatarUrl }}" 
                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ce202a&color=fff&bold=true';"
                 alt="Avatar de {{ Auth::user()->name }}" 
                 class="w-9 h-9 rounded-lg object-cover shrink-0 border-2 border-gray-200 dark:border-slate-600 shadow-sm transition-all duration-300">
            
            <div x-show="sidebarOpen" x-transition.opacity class="ml-3 min-w-0 flex-1">
                <div class="text-sm font-bold text-gray-900 dark:text-white truncate">
                    {{ Auth::user()->name }}
                </div>
                <div class="text-[10px] text-red-600 dark:text-red-400 font-bold uppercase tracking-widest truncate">
                    {{ Auth::user()->role }}
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center py-2.5 text-sm font-bold text-gray-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-all group" 
                    :class="sidebarOpen ? 'px-4 justify-start' : 'px-0 justify-center'"
                    title="Cerrar Sesión">
                {{-- Ícono SVG de Salida (Reemplazo de la puerta) --}}
                <span class="flex-shrink-0 w-8 flex justify-center group-hover:scale-110 transition-transform text-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </span>
                <span x-show="sidebarOpen" x-transition.opacity class="ml-1 font-bold whitespace-nowrap">Cerrar Sesión</span>
            </button>
        </form>
    </div>
</aside>