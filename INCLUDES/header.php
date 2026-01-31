<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función auxiliar para verificar si es admin
function esAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';
}
?>
<head>
    <link rel="stylesheet" href="../ASSETS/css/components.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<nav class="flex items-center justify-between px-8 py-4 bg-white shadow-md w-full">
    <div class="flex items-center">
        <a href="index.php">
            <img src="../ASSETS/img/logo.png" alt="Logo" class="h-10 w-auto hover:opacity-80 transition">
        </a>
    </div>

    <div class="hidden md:flex gap-8">
        <a href="index.php" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Inicio</a>
        <a href="buscarEmpleo.php" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Buscar ofertas</a>
        <a href="crearEmpleo.php" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Publicar empleo</a>
        
        <?php if (esAdmin()): ?>
            <a href="dashboard.php" class="text-purple-600 hover:text-purple-700 font-semibold transition-colors flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Administración
            </a>
        <?php endif; ?>
    </div>

    <div class="flex items-center gap-4">
        <?php if (isset($_SESSION['nombre'])): ?>
            <div class="relative group">
                <button class="flex items-center gap-2 focus:outline-none">
                    <div class="w-10 h-10 <?php echo esAdmin() ? 'bg-purple-100' : 'bg-blue-100'; ?> rounded-full flex items-center justify-center border-2 border-transparent group-hover:border-<?php echo esAdmin() ? 'purple' : 'blue'; ?>-400 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 <?php echo esAdmin() ? 'text-purple-600' : 'text-blue-600'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="hidden sm:block text-sm font-medium text-gray-700">
                        <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Mi Perfil'); ?>
                        <?php if (esAdmin()): ?>
                            <span class="text-xs text-purple-600 font-bold ml-1">(Admin)</span>
                        <?php endif; ?>
                    </span>
                </button>

                <div class="absolute right-0 mt-0 w-48 bg-white border border-gray-100 shadow-xl rounded-xl py-2 z-50 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200 transform origin-top-right">
                    <div class="px-4 py-2 border-bottom border-gray-50 text-xs text-gray-400 uppercase font-bold tracking-wider">
                        Cuenta
                    </div>
                    <a href="perfil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">Ver perfil</a>
                   
                    <?php if (esAdmin()): ?>
                        <hr class="my-1 border-gray-100">
                        <div class="px-4 py-2 text-xs text-purple-400 uppercase font-bold tracking-wider">
                            Administración
                        </div>
                        <a href="admin/dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition">Panel de control</a>
                        <a href="admin/usuarios.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition">Gestionar usuarios</a>
                        <a href="admin/empleos.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition">Gestionar empleos</a>
                    <?php endif; ?>

                    <hr class="my-1 border-gray-100">
                    <a href="../AUTH/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-semibold transition">Cerrar sesión</a>
                </div>
            </div>

        <?php else: ?>
            <a href="login.php" class="text-gray-600 hover:text-blue-600 font-medium transition">Iniciar sesión</a>
            <a href="register.php" class="btn-primary !w-auto inline-flex items-center justify-center">
                Registrarse
            </a>
        <?php endif; ?>
    </div>
</nav>