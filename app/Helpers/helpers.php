<?php

if (!function_exists('sidebarLink')) {
    function sidebarLink($url, $icon, $label, $routeName, $current = null) {
        
        // Verificamos si la ruta actual coincide con la del botón (le pongo un * por si son sub-rutas)
        $active = request()->routeIs($routeName . '*');
        
        // Colores ajustados: Brillo al máximo en el modo oscuro para que no se pierdan
        $activeClass = $active 
            ? 'bg-[#10b981] text-white shadow-md font-bold dark:bg-[#10b981] dark:text-white' 
            : 'text-gray-600 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800/50 hover:text-gray-900 dark:hover:text-white font-medium transition-colors';
        
        // Devolvemos el HTML con las variables de Alpine.js incrustadas
        return "
            <a href='{$url}' class='flex items-center px-6 py-3 mx-2 rounded-xl transition-all group {$activeClass}'>
                <span class='text-xl flex-shrink-0'>{$icon}</span>
                <span x-show='sidebarOpen' x-transition.opacity class='ml-4 whitespace-nowrap'>{$label}</span>
            </a>
        ";
    }
}