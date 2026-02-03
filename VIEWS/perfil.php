<?php
session_start();

if (empty($_SESSION["logueado"]) || empty($_SESSION["usuario_id"])) {
  header("Location: login.php?error=login_required");
  exit();
}

require_once("../config/db.php");
require_once("../classes/usuario.php");
require_once("../DAO/usuarioDAO.php");

$usuarioDAO = new UsuarioDAO($conn);
$u = $usuarioDAO->obtenerPorId((int)$_SESSION["usuario_id"]);

if (!$u) {
  // Si no existe en BBDD (cuenta borrada, etc.)
  session_destroy();
  header("Location: login.php?error=login_required");
  exit();
}

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>


<body class="min-h-screen bg-gray-50">

  <?php include("../INCLUDES/header.php"); ?>



  <!-- modalOverlay aquí -->


  <main class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex items-end justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-semibold">Perfil</h1>
        <p class="text-sm text-gray-600">Gestiona tu cuenta (nombre, email, contraseña y eliminación).</p>
      </div>
      <a href="index.php" class="text-sm underline text-gray-700 hover:text-black">Volver</a>
    </div>

    <!-- Mensajes -->
    <div id="toast" class="hidden mb-4 p-4 rounded-lg border"></div>

    <!-- “Display” tipo tabla como tu captura -->
    <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full min-w-[900px] table-fixed text-left">
      <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-600">
        <tr>
          <th class="px-6 py-4 w-[26%]">Nombre</th>
          <th class="px-6 py-4 w-[30%]">Email</th>
          <th class="px-6 py-4 w-[16%]">Rol</th>
          <th class="px-6 py-4 w-[16%]">Fecha</th>
          <th class="px-6 py-4 w-[12%] text-right">Acciones</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-100">
        <tr class="hover:bg-gray-50/60 align-middle">
          <td class="px-6 py-5 font-medium text-gray-800 whitespace-nowrap" id="tdNombre">
            <?= h($u["nombre_apellido"]) ?>
          </td>

          <td class="px-6 py-5 text-gray-700 whitespace-nowrap" id="tdEmail">
            <?= h($u["email"]) ?>
          </td>

          <td class="px-6 py-5 text-gray-700 whitespace-nowrap">
            <?= h($u["rol"]) ?>
          </td>

          <td class="px-6 py-5 text-gray-700 whitespace-nowrap">
            <?= h(substr((string)$u["fecha_registro"], 0, 10)) ?>
          </td>

          <td class="px-6 py-5">
            <div class="flex items-center justify-end gap-2">
              <!-- Editar perfil -->
              <button id="btnEditPerfil"
                      class="p-2 rounded-md hover:bg-gray-100"
                      type="button"
                      title="Editar perfil"
                      aria-label="Editar perfil">
                ✏️
              </button>

              <!-- Eliminar cuenta -->
              <button id="btnDeletePerfil"
                      class="p-2 rounded-md hover:bg-gray-100 text-red-600"
                      type="button"
                      title="Eliminar cuenta"
                      aria-label="Eliminar cuenta">
                🗑️
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</section>

  </main>

  <!-- MODAL base -->
  <div id="modalOverlay" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
    <div id="modalBox" class="w-full max-w-lg bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 id="modalTitle" class="text-lg font-semibold">Modal</h2>
        <button id="modalClose" class="p-2 rounded-md hover:bg-gray-100" type="button" aria-label="Cerrar">✕</button>
      </div>

      <div class="px-5 py-5">
        <!-- Contenido dinámico -->
        <div id="modalBody"></div>

        <div class="mt-6 flex items-center justify-end gap-2">
          <button id="modalCancel" class="px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-50" type="button">Cancelar</button>
          <button id="modalOk" class="px-4 py-2 rounded-md bg-black text-white hover:bg-gray-800" type="button">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
<?php include("../INCLUDES/footer.php"); ?> 
  <script>
  window.__PERFIL_API__ = "../ASSETS/API/perfil.php";
</script>
  <script src="../ASSETS/js/perfil.js?v=1" defer></script>

    
   
</body>
</html>
