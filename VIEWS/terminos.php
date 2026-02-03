<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones - Empleo360</title>
    <link rel="stylesheet" href="../ASSETS/css/components.css">
    <script src="https://cdn.tailwindcss.com"></script>
        <style type="text/tailwindcss">
    @layer components {
        .legal-content h3 { @apply text-2xl font-bold text-slate-800 mt-8 mb-4 border-b pb-2; }
        .legal-content h4 { @apply text-xl font-semibold text-slate-700 mt-6 mb-3; }
        .legal-content p { @apply text-gray-600 leading-relaxed mb-4 text-justify; }
        .legal-content ul { @apply list-disc pl-6 mb-4 text-gray-600; }
    }

    @media (max-width: 640px) {
        .legal-content p { @apply text-left text-sm !important; }
        .legal-content h3 { @apply text-xl !important; }
        .legal-content h4 { @apply text-lg !important; }
        .panel__inner { @apply p-6 !important; }
        .page-title { @apply text-3xl !important; }
    }
</style>
</head>
<body class="
  bg-[linear-gradient(135deg,#F5F4F0_0%,#F2F2EE_40%,#EDECE8_100%)]
">


    <?php include_once '../includes/header.php'; ?>

    <main class="container mx-auto py-12 px-4">
        <div class="panel max-w-5xl mx-auto overflow-hidden">
            <div class="panel__inner p-8 md:p-12">
                
                <header class="text-center mb-12">
                    <h1 class="page-title !text-4xl">Términos y Condiciones</h1>
                    <p class="page-subtitle">Reglas de uso y compromisos de la comunidad <span class="highlight">Empleo360</span></p>
                    <div class="divider"></div>
                </header>

                <div class="legal-content">
                    
                    <h3>1. RELACIÓN CONTRACTUAL</h3>
                    <p>
                        Las presentes condiciones regulan el uso de la plataforma <strong>Empleo360</strong>. Al registrarse, el usuario acepta de manera íntegra estas normas. Si no está de acuerdo con alguna de ellas, deberá abstenerse de utilizar los servicios de la plataforma.
                    </p>

                    <h3>2. SERVICIOS DE LA PLATAFORMA</h3>
                    <p>
                        Empleo360 ofrece una herramienta tecnológica para la visualización de ofertas de empleo y la gestión de perfiles profesionales. El Usuario entiende que:
                    </p>
                    <ul>
                        <li>Empleo360 <strong>no es una agencia de colocación</strong>, sino un escaparate de información.</li>
                        <li>La plataforma no garantiza la obtención de un puesto de trabajo por el mero hecho de usar el servicio.</li>
                        <li>La veracidad de las ofertas externas es responsabilidad del portal de origen.</li>
                    </ul>

                    <h3>3. CUENTAS DE USUARIO</h3>
                    <p>
                        Para acceder a ciertas funciones (como modificar perfiles), el usuario debe crear una cuenta. El usuario se compromete a:
                    </p>
                    <div class="info-box bg-slate-50 p-4 rounded border mb-4">
                        <ul>
                            <li>Mantener la confidencialidad de su contraseña.</li>
                            <li>No suplantar la identidad de otras personas o empresas.</li>
                            <li>Proporcionar información académica y profesional verídica.</li>
                        </ul>
                    </div>

                    <h3>4. CONDUCTA PROHIBIDA</h3>
                    <p>Para mantener un entorno profesional, queda terminantemente prohibido:</p>
                    <div class="rule-box">
                        <ul class="text-red-900 font-medium">
                            <li>Publicar contenido ofensivo, discriminatorio o violento.</li>
                            <li>Utilizar scripts, bots o herramientas automatizadas para extraer datos del mapa o de las listas de empleo.</li>
                            <li>Publicar ofertas de empleo falsas o engañosas (piramidales, servicios eróticos, etc.).</li>
                            <li>Cualquier acción que sature o dañe los servidores de Empleo360.</li>
                        </ul>
                    </div>

                    <h3>5. PROPIEDAD DE LOS DATOS</h3>
                    <p>
                        El usuario conserva los derechos sobre su currículum y datos personales, pero otorga a Empleo360 una licencia limitada para procesar dicha información con el fin de conectar su perfil con potenciales empleadores o mostrar su ubicación en el mapa de candidatos si así lo desea.
                    </p>

                    <h3>6. LIMITACIÓN DE RESPONSABILIDAD</h3>
                    <p>
                        Empleo360 realiza sus mejores esfuerzos para mantener la plataforma libre de errores, pero no se hace responsable de:
                    </p>
                    <ul>
                        <li>La veracidad de los datos introducidos por otros usuarios.</li>
                        <li>Decisiones de contratación tomadas por empresas externas.</li>
                        <li>Problemas técnicos derivados de la conexión a internet del usuario.</li>
                    </ul>

                    <h3>7. MODIFICACIONES</h3>
                    <p>
                        Nos reservamos el derecho de actualizar estos términos en cualquier momento. El uso continuado de la web tras una actualización implica la aceptación de los nuevos términos.
                    </p>

                </div>

                <footer class="mt-12 pt-6 border-t text-sm text-gray-400 text-center italic">
                    Términos y condiciones actualizados al: 02 de febrero de 2026.
                </footer>

            </div>
        </div>
    </main>

    <?php include_once '../includes/footer.php'; ?> 

</body>
</html>