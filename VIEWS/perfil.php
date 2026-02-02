<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION["logueado"]) || $_SESSION["logueado"] !== true) {
    header("Location: login.php?error=login_required");
    exit();
}

require_once("../CONFIG/db.php");
require_once("../CLASSES/usuario.php");
require_once("../DAO/usuarioDAO.php");

$usuarioDAO = new UsuarioDAO($conn);

$usuarioId = (int)($_SESSION["usuario_id"] ?? 0);
if ($usuarioId <= 0) {
    header("Location: ../AUTH/logout.php");
    exit();
}

$usuarioArr = $usuarioDAO->obtenerPorId($usuarioId);
if ($usuarioArr === null) {
    header("Location: ../AUTH/logout.php");
    exit();
}

$nombre = $_SESSION["nombre"] ?? ($usuarioArr["nombre_apellido"] ?? "");
$email = $_SESSION["email"] ?? ($usuarioArr["email"] ?? "");
?>
<!DOCTYPE html>
<html lang="es">
<?php require_once("../INCLUDES/header.php"); ?>

<body class="bg-gray-50 min-h-screen">

<main class="max-w-4xl mx-auto px-6 py-10">
    <h1 class="text-2xl font-bold text-gray-800">Mi perfil</h1>
    <p class="text-gray-600 mt-1">Gestiona tu cuenta: nombre, email y contraseña.</p>

    <section class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800">Datos actuales</h2>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide">ID</p>
                <p class="mt-1 text-gray-800 font-medium"><?php echo (int)$usuarioId; ?></p>
            </div>

            <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide">Email</p>
                <p class="mt-1 text-gray-800 font-medium"><?php echo htmlspecialchars($email); ?></p>
            </div>
        </div>

        <hr class="my-6 border-gray-100">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-gray-800">Cambiar nombre</h3>
                <p class="text-sm text-gray-600 mt-1">Este cambio se aplica directamente.</p>

                <form id="formNombre" class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre y apellidos</label>
                    <input
                        type="text"
                        name="nombre_apellido"
                        value="<?php echo htmlspecialchars($nombre); ?>"
                        class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        required
                        minlength="3"
                        maxlength="100"
                    >
                    <button
                        type="submit"
                        class="mt-3 inline-flex items-center justify-center px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition"
                    >
                        Guardar nombre
                    </button>
                </form>
            </div>

            <div>
                <h3 class="font-semibold text-gray-800">Cambios sensibles</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Para cambiar <b>email</b> o <b>contraseña</b> te enviaremos un código al <b>email actual</b>.
                </p>

                <button
                    id="abrirModalPerfil"
                    class="mt-4 inline-flex items-center justify-center px-4 py-2 rounded-xl bg-gray-900 text-white font-semibold hover:bg-black transition"
                    type="button"
                >
                    Cambiar email o contraseña
                </button>
            </div>
        </div>
    </section>
</main>

<!-- Overlay -->
<div id="overlayPerfil" class="hidden fixed inset-0 bg-black/40 z-40"></div>

<!-- Modal -->
<div id="perfilModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Verificación de cambios</h3>
            <button id="cerrarModalPerfil" class="text-gray-500 hover:text-gray-800 text-xl leading-none" type="button">×</button>
        </div>

        <div class="p-5">
            <div class="grid grid-cols-1 gap-4">
                <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 text-sm text-blue-800">
                    El código se envía a: <b><?php echo htmlspecialchars($email); ?></b>
                </div>

                <div id="perfilMsg" class="hidden"></div>

                <!-- Paso 1: pedir código -->
                <div id="stepNewValue">
                    <form id="formSendCode" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Qué quieres cambiar</label>
                            <select id="tipoCambio" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2">
                                <option value="email">Email</option>
                                <option value="password">Contraseña</option>
                            </select>
                        </div>

                        <div id="wrapEmail">
                            <label class="block text-sm font-medium text-gray-700">Nuevo email</label>
                            <input id="newEmail" type="email"
                                   class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                   placeholder="nuevo@email.com">
                        </div>

                        <div id="wrapPassword" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
                            <input id="newPassword" type="password"
                                   class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                   placeholder="Mínimo 6 caracteres">
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                            Enviar código de verificación
                        </button>

                        <p class="text-xs text-gray-500">
                            * En local, <code>mail()</code> puede no enviar si no hay SMTP configurado. En hosting irá bien.
                        </p>
                    </form>
                </div>

                <!-- Paso 2: verificar código -->
                <div id="stepCode" class="hidden">
                    <form id="formVerify" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Código de 6 dígitos</label>
                            <input name="code" type="text" inputmode="numeric" pattern="\d{6}" maxlength="6"
                                   class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2 tracking-widest text-center text-lg focus:outline-none focus:ring-2 focus:ring-blue-200"
                                   placeholder="123456" required>
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl bg-gray-900 text-white font-semibold hover:bg-black transition">
                            Verificar y aplicar cambios
                        </button>

                        <button type="button"
                                class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition"
                                onclick="location.reload()">
                            Cancelar
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="../ASSETS/js/perfil.js"></script>
</body>
</html>
