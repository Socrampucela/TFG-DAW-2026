<?php
include_once '../CLASSES/usuario.php';
include_once '../includes/header.php';
include_once "../DAO/empleoDAO.php";
include_once "../config/db.php";

$empleoDAO = new EmpleoDAO($conn);

if(esAdmin()){
    $estadisticas = $empleoDAO->devolverEmpleoProvincia();

    $estadisticasFechas = $empleoDAO->obtenerEmpleosPorDia(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                principal: {
                  100: '#dbeafe',
                  500: '#3b82f6',
                  600: '#2563eb',
                  700: '#1d4ed8',
                }
              }
            }
          }
        }
    </script>
    <style>
        #estadisticas { width: 450px; margin: 20px auto; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="max-w-6xl mx-auto my-10 bg-white shadow-xl rounded-xl overflow-hidden flex flex-col md:flex-row border border-gray-200">
        
        <?php 
            $pagina_actual = 'dashboard'; 
            include_once "../includes/sideNavAdmin.php"; 
        ?>

        <main class="flex-1 p-8">
            <div id="dashboard" class="seccion-admin">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Panel de Control</h1>
                        <p class="text-gray-400 text-sm mt-1">Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4 border-t border-gray-50 pt-6">
                    <h3 class="text-lg font-semibold text-gray-700 tracking-tight">Estadísticas de la plataforma</h3>
                    
                    <select id="selectorGrafico" class="bg-white border border-gray-200 text-gray-600 text-sm py-2 px-4 pr-8 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-principal-500 transition-all cursor-pointer">
                        <option value="provincia">📍 Empleos por Provincia </option>
                        <option value="fecha">📅 Ofertas por Día </option>
                    </select>
                </div>

                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 shadow-inner relative h-[450px]">
                    <canvas id="graficoAdmin"></canvas>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Pasamos los datos del DAO al archivo JS externo
        const datosProvincias = <?= json_encode($estadisticas ?? []); ?>;
        const datosFechas = <?= json_encode($estadisticasFechas ?? []); ?>;
    </script>

    <script src="../ASSETS/js/estadisticas.js"></script>
    <?php  include '../INCLUDES/footer.php'; ?>
</body>
</html>
<?php
} else {
    header("Location: index.php");
    exit();
}