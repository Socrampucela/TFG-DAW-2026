<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - Empleo360</title>
    
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
<body class="bg-gray-50">

    <?php include_once '../includes/header.php'; ?>

    <main class="container mx-auto py-12 px-4">
        <div class="panel max-w-5xl mx-auto overflow-hidden">
            <div class="panel__inner p-8 md:p-12">
                
                <header class="text-center mb-12">
                    <h1 class="page-title !text-4xl">Política de Privacidad</h1>
                    <p class="page-subtitle">Portal Web: <span class="font-bold text-blue-600">www.empleo360.es</span></p>
                    <div class="divider"></div>
                </header>

                <div class="legal-content">
                    <h3>I. POLÍTICA DE PRIVACIDAD Y PROTECCIÓN DE DATOS</h3>
                    <p>
                        Respetando lo establecido en la legislación vigente, <span class="highlight">Empleo360</span> se compromete a adoptar las medidas técnicas y organizativas necesarias, según el nivel de seguridad adecuado al riesgo de los datos recogidos.
                    </p>

                    <h4>Leyes que incorpora esta política de privacidad</h4>
                    <p>Esta política está adaptada a la normativa española y europea vigente:</p>
                    <ul>
                        <li>El Reglamento General de Protección de Datos <strong>(RGPD)</strong>.</li>
                        <li>La Ley Orgánica de Protección de Datos Personales <strong>(LOPD-GDD)</strong>.</li>
                        <li>La Ley de Servicios de la Sociedad de la Información <strong>(LSSI-CE)</strong>.</li>
                    </ul>

                    <h4>Identidad del responsable</h4> 
                    <div class="bg-slate-50 p-6 rounded-lg border border-slate-200 mb-6">
                        <p><strong>Titular:</strong> Empleo360 S.L</p>
                        <p><strong>NIF/CIF:</strong> 81241951B</p>
                        <p><strong>Email:</strong> <a href="mailto:marcos.poumad.1@educa.jcyl.es" class="text-blue-600 underline">marcos.poumad.1@educa.jcyl.es</a></p>
                        <p><strong>Email:</strong> <a href="mailto:alejandro.morrod.1@educa.jcyl.es" class="text-blue-600 underline">alejandro.morrod.1@educa.jcyl.es</a></p>
                    </div>

                    <h4>Finalidad del tratamiento</h4>
                    <p>
                        Los datos personales son recabados con la finalidad de facilitar, agilizar y cumplir los compromisos establecidos entre el Sitio Web y el Usuario, específicamente para la <strong>gestión de ofertas de empleo y perfiles profesionales</strong>.
                    </p>

                    <h4>Tus Derechos (Derechos ARCO)</h4>
                    <p>Como usuario, tienes derecho a:</p>
                    <ul>
                        <li><strong>Acceso:</strong> Saber qué datos tenemos de ti.</li>
                        <li><strong>Rectificación:</strong> Corregir información inexacta.</li>
                        <li><strong>Supresión:</strong> Solicitar que borremos tus datos ("derecho al olvido").</li>
                        <li><strong>Oposición:</strong> Pedir que no tratemos tus datos para ciertos fines.</li>
                    </ul>
                    <p>Puedes ejercer estos derechos enviando un correo a la dirección arriba indicada adjuntando una copia de tu DNI.</p>

                    <h3>II. ACEPTACIÓN Y CAMBIOS</h3>
                    <p>
                        El uso del Sitio Web implicará la aceptación de la Política de Privacidad del mismo. Empleo360 se reserva el derecho a modificar esta política para adaptarla a novedades legislativas.
                    </p>
                </div>

                <footer class="mt-12 pt-6 border-t text-sm text-gray-400 text-center">
                    Última actualización: 02 de febrero de 2026
                </footer>

            </div>
        </div>
    </main>

    <?php include_once '../includes/footer.php'; ?> 

</body>
</html>