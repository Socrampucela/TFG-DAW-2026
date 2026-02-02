<?php
include_once '../CLASSES/usuario.php';
include_once '../includes/header.php';
include_once "../DAO/empleoDAO.php";
include_once "../config/db.php";

$empleoDAO = new EmpleoDAO($conn);

if(esAdmin()){
    $estadisticas = $empleoDAO->devolverEmpleoProvincia();
    $estadisticasFechas = $empleoDAO->obtenerEmpleosPorDia()
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zona de administrador</title>
    <style>
        #estadisticas { width: 450px; margin: 20px auto; }
    </style>
</head>
<body>


   <div class="max-w-6xl mx-auto my-10 bg-white shadow-2xl rounded-xl overflow-hidden flex flex-col md:flex-row border border-gray-100">
    
    <aside id="apartadosAdministrador" class="w-full md:w-64 bg-gray-50 border-r border-gray-200 p-6 flex flex-col gap-2">
        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Menú Principal</h2>
        
        <button onclick="mostrarSeccion('dashboard')" class="flex items-center gap-3 px-4 py-2 text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-principal-50 hover:text-blue-600 transition-all font-medium">
            <span class="text-xl">📊</span> Panel de Control
        </button>
        
        <button onclick="location.href='usuarios.php'" class="flex items-center gap-3 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-all font-medium">
            <span class="text-xl">👥</span> Gestionar Usuarios
        </button>
        
        <button onclick="location.href='ofertas.php'" class="flex items-center gap-3 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-all font-medium">
            <span class="text-xl">💼</span> Gestionar Ofertas
        </button>
        

        <div class="mt-auto pt-6 border-t border-gray-200">
            <a href="../AUTH/logout.php" class="text-sm text-red-500 hover:underline font-semibold">Cerrar Sesión</a>
        </div>
    </aside>

    <main class="flex-1 p-8 bg-white">
        <div id="dashboard" class="seccion-admin">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Panel de Control</h1>
                    <p class="text-gray-500">Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?></p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <h3 class="text-lg font-bold text-gray-700">Visualización de Datos</h3>
                
                <select id="selectorGrafico" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="provincia">📍 Empleos por Provincia </option>
                    <option value="fecha">📅 Ofertas por Día </option>
                </select>
            </div>
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 shadow-inner relative h-[450px]">
                <canvas id="graficoAdmin"></canvas>
            </div>
            </div>
        </div>
    </main>
</div>
    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const datosProvincias = <?= json_encode($estadisticas ?? []); ?>;
        const datosFechas = <?= json_encode($estadisticasFechas ?? []); ?>;
    </script>

    <script src="../ASSETS/js/estadisticas.js"></script>
</body>
</html>
<?php
} else {
    header("Location: index.php");
    exit();
}