<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<head>
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
        <a href="ofertas.php" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Buscar ofertas</a>
        <a href="publicar.php" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Publicar empleo</a>
    </div>

    <div class="flex items-center gap-4">
        <?php if (isset($_SESSION['nombre'])): ?>
            <div class="relative group">
                <button class="flex items-center gap-2 focus:outline-none">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center border-2 border-transparent group-hover:border-blue-400 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="hidden sm:block text-sm font-medium text-gray-700">
                        <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Mi Perfil'); ?>
                    </span>
                </button>

                <div class="absolute right-0 mt-0 w-48 bg-white border border-gray-100 shadow-xl rounded-xl py-2 z-50 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200 transform origin-top-right">
                    <div class="px-4 py-2 border-bottom border-gray-50 text-xs text-gray-400 uppercase font-bold tracking-wider">
                        Cuenta
                    </div>
                    <a href="perfil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">Ver perfil</a>
                   
                    <hr class="my-1 border-gray-100">
                    <a href="../AUTH/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-semibold transition">Cerrar sesión</a>
                </div>
            </div>

        <?php else: ?>
            <a href="login.php" class="text-gray-600 hover:text-blue-600 font-medium transition">Iniciar sesión</a>
            <a href="register.php" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition shadow-md hover:shadow-lg">
                Registrarse
            </a>
        <?php endif; ?>
    </div>
</nav>