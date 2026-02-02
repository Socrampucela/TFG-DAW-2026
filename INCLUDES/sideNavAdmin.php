<aside class="w-full md:w-64 bg-gray-50 border-r border-gray-200 p-6 flex flex-col gap-2">
    <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Menú Principal</h2>
    
    <a href="dashboard.php" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all font-medium 
        <?= ($pagina_actual == 'dashboard') ? 'text-principal-600 bg-white border border-principal-100 shadow-sm' : 'text-gray-600 hover:bg-gray-100' ?>">
        <span class="text-xl">📊</span> Panel de Control
    </a>
    
    <a href="usuarios.php" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all font-medium 
        <?= ($pagina_actual == 'usuarios') ? 'text-principal-600 bg-white border border-principal-100 shadow-sm' : 'text-gray-600 hover:bg-gray-100' ?>">
        <span class="text-xl">👥</span> Gestionar Usuarios
    </a>
    
    <a href="ofertas.php" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all font-medium 
        <?= ($pagina_actual == 'ofertas') ? 'text-principal-600 bg-white border border-principal-100 shadow-sm' : 'text-gray-600 hover:bg-gray-100' ?>">
        <span class="text-xl">💼</span> Gestionar Ofertas
    </a>

    <div class="mt-auto pt-6 border-t border-gray-200">
        <a href="../AUTH/logout.php" class="text-sm text-red-500 hover:underline font-semibold">Cerrar Sesión</a>
    </div>
</aside>
