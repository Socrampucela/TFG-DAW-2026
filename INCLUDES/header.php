<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función auxiliar para verificar si es admin
if (!function_exists('esAdmin')) {
    function esAdmin() {
        return isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';
    }
}
?>
<head>
    <link rel="stylesheet" href="../ASSETS/css/components.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Ajustes para el header responsivo */
        @media (max-width: 768px) {
            .nav-container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            /* Animación suave para el menú móvil */
            #mobile-menu {
                transition: all 0.3s ease-in-out;
                transform-origin: top;
            }
        }
        
        .mobile-link-active {
            background-color: rgba(56, 130, 182, 0.1);
            color: var(--c-secondary);
        }
    </style>
</head>

<nav class="nav-container flex items-center justify-between px-8 py-4 bg-white shadow-md w-full relative z-[100]">
    <div class="flex items-center">
        <a href="index.php" class="z-50">
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

    <div class="flex items-center gap-2 sm:gap-4">
        <?php if (isset($_SESSION['nombre'])): ?>
            <div class="relative group">
                <button class="flex items-center gap-2 focus:outline-none">
                    <div class="w-10 h-10 <?php echo esAdmin() ? 'bg-purple-100' : 'bg-blue-100'; ?> rounded-full flex items-center justify-center border-2 border-transparent group-hover:border-<?php echo esAdmin() ? 'purple' : 'blue'; ?>-400 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 <?php echo esAdmin() ? 'text-purple-600' : 'text-blue-600'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="hidden lg:block text-sm font-medium text-gray-700">
                        <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                    </span>
                </button>

                <div class="absolute right-0 mt-0 w-48 bg-white border border-gray-100 shadow-xl rounded-xl py-2 z-50 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200 transform origin-top-right">
                    <div class="px-4 py-2 border-bottom border-gray-50 text-xs text-gray-400 uppercase font-bold tracking-wider">Cuenta</div>
                    <a href="perfil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition">Ver perfil</a>
                    
                    <?php if (esAdmin()): ?>
                        <hr class="my-1 border-gray-100">
                        <div class="px-4 py-2 text-xs text-purple-400 uppercase font-bold tracking-wider">Admin</div>
                        <a href="dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 transition">Panel de control</a>
                        <a href="ofertas.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 transition">Gestionar empleos</a>
                    <?php endif; ?>

                    <hr class="my-1 border-gray-100">
                    <a href="../AUTH/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-semibold transition">Cerrar sesión</a>
                </div>
            </div>

        <?php else: ?>
            <a href="login.php" class="hidden sm:block text-gray-600 hover:text-blue-600 font-medium transition">Entrar</a>
            <a href="register.php" class="btn-primary !w-auto !px-4 !py-2 text-sm shadow-none">Registrarse</a>
        <?php endif; ?>

        <button id="menu-btn" class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <div id="mobile-menu" class="hidden absolute top-full left-0 w-full bg-white border-t border-gray-100 shadow-2xl flex-col p-4 gap-2 z-[90] md:hidden transform scale-y-0 opacity-0 transition-all">
        <a href="index.php" class="text-gray-600 font-medium p-3 hover:bg-gray-50 rounded-lg flex items-center gap-3 transition">
            <span class="text-lg">🏠</span> Inicio
        </a>
        <a href="buscarEmpleo.php" class="text-gray-600 font-medium p-3 hover:bg-gray-50 rounded-lg flex items-center gap-3 transition">
            <span class="text-lg">🔍</span> Buscar ofertas
        </a>
        <a href="crearEmpleo.php" class="text-gray-600 font-medium p-3 hover:bg-gray-50 rounded-lg flex items-center gap-3 transition">
            <span class="text-lg">➕</span> Publicar empleo
        </a>

        <?php if (esAdmin()): ?>
            <div class="mt-2 pt-2 border-t border-purple-100">
                <p class="px-3 text-[10px] uppercase font-bold text-purple-400 tracking-widest mb-2">Administración</p>
                <a href="dashboard.php" class="text-purple-700 font-medium p-3 hover:bg-purple-50 rounded-lg flex items-center gap-3 transition">
                    <span class="text-lg">📊</span> Dashboard
                </a>
                <a href="usuarios.php" class="text-purple-700 font-medium p-3 hover:bg-purple-50 rounded-lg flex items-center gap-3 transition">
                    <span class="text-lg">👥</span> Usuarios
                </a>
                <a href="ofertas.php" class="text-purple-700 font-medium p-3 hover:bg-purple-50 rounded-lg flex items-center gap-3 transition">
                    <span class="text-lg">💼</span> Gestionar Ofertas
                </a>
            </div>
        <?php endif; ?>

        <div class="mt-2 pt-2 border-t border-gray-100">
            <?php if (isset($_SESSION['nombre'])): ?>
                <a href="perfil.php" class="text-gray-600 font-medium p-3 hover:bg-gray-50 rounded-lg flex items-center gap-3 transition">
                    <span class="text-lg">👤</span> Mi Perfil
                </a>
                <a href="../AUTH/logout.php" class="text-red-600 font-bold p-3 hover:bg-red-50 rounded-lg flex items-center gap-3 transition">
                    <span class="text-lg">🚪</span> Cerrar sesión
                </a>
            <?php else: ?>
                <a href="login.php" class="text-blue-600 font-bold p-3 hover:bg-blue-50 rounded-lg text-center transition">Iniciar sesión</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('menu-btn');
        const menu = document.getElementById('mobile-menu');
        const icon = document.getElementById('menu-icon');

        btn.addEventListener('click', () => {
            const isHidden = menu.classList.contains('hidden');
            
            if (isHidden) {
                // Abrir menú
                menu.classList.remove('hidden');
                setTimeout(() => {
                    menu.classList.add('flex', 'scale-y-100', 'opacity-100');
                    menu.classList.remove('scale-y-0', 'opacity-0');
                }, 10);
                icon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
            } else {
                // Cerrar menú
                menu.classList.add('scale-y-0', 'opacity-0');
                menu.classList.remove('scale-y-100', 'opacity-100');
                setTimeout(() => {
                    menu.classList.add('hidden');
                    menu.classList.remove('flex');
                }, 300);
                icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            }
        });

        // Cerrar menú al hacer click fuera
        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && !btn.contains(e.target) && !menu.classList.contains('hidden')) {
                btn.click();
            }
        });
    });
</script>