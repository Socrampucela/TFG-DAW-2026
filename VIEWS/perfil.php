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


<body class="
  min-h-screen flex flex-col
  bg-[linear-gradient(135deg,#F5F4F0_0%,#F2F2EE_40%,#EDECE8_100%)]
">



  <?php include("../INCLUDES/header.php"); ?>



  <!-- modalOverlay aquí -->


  <main class="flex-1 max-w-6xl mx-auto px-4 py-8">

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
  <div class="p-6 sm:p-8">
    <div class="flex items-start justify-between gap-4">
      <div>
        <h2 class="text-lg font-semibold text-gray-900">Datos de tu cuenta</h2>
        <p class="text-sm text-gray-600 mt-1">Revisa y gestiona la información del perfil.</p>
      </div>

      <div class="flex items-center gap-2">
        <button id="btnEditPerfil"
                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-sm font-semibold"
                type="button"
                title="Editar perfil"
                aria-label="Editar perfil">
          ✏️ <span class="hidden sm:inline">Editar</span>
        </button>

        <button id="btnDeletePerfil"
                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-red-200 hover:bg-red-50 text-sm font-semibold text-red-700"
                type="button"
                title="Eliminar cuenta"
                aria-label="Eliminar cuenta">
          🗑️ <span class="hidden sm:inline">Eliminar</span>
        </button>
      </div>
    </div>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
      <!-- Nombre -->
      <div class="rounded-xl border border-gray-200 bg-white p-4">
        <p class="text-xs font-bold tracking-widest text-gray-500 uppercase">Nombre</p>
        <p class="mt-2 text-gray-900 font-semibold" id="tdNombre">
          <?= h($u["nombre_apellido"]) ?>
        </p>
      </div>

      <!-- Email -->
      <div class="rounded-xl border border-gray-200 bg-white p-4">
        <p class="text-xs font-bold tracking-widest text-gray-500 uppercase">Email</p>
        <p class="mt-2 text-gray-900 font-semibold break-all" id="tdEmail">
          <?= h($u["email"]) ?>
        </p>
      </div>

      <!-- Rol -->
      <div class="rounded-xl border border-gray-200 bg-white p-4">
        <p class="text-xs font-bold tracking-widest text-gray-500 uppercase">Rol</p>
        <p class="mt-2 text-gray-900 font-semibold">
          <?= h($u["rol"]) ?>
        </p>
      </div>

      <!-- Fecha -->
      <div class="rounded-xl border border-gray-200 bg-white p-4">
        <p class="text-xs font-bold tracking-widest text-gray-500 uppercase">Fecha de registro</p>
        <p class="mt-2 text-gray-900 font-semibold">
          <?= h(substr((string)$u["fecha_registro"], 0, 10)) ?>
        </p>
      </div>
    </div>
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
