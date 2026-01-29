<?php
include "../INCLUDES/header.php";

if(isset($_SESSION['nombre'])){ ?>
<main class="flex-grow flex justify-center items-start py-10 px-4">
       
        <section class="panel" style="width:100%; max-width:520px;">
            <div class="panel__inner">

                <h1 class="page-title">Crear una oferta de empleo</h1>
                <p class="page-subtitle">Crea una oferta para encontrar al trabajador ideal.</p>
                <div id="divErrores"></div>
                <form class="form" action="../AUTH/procesarEmpleo.php" method="post" id="formulario" novalidate>
                    
                    <div>
                        <label class="form-label" for="titulo">Título de la oferta:</label>
                        <input class="form-input" type="text" id="titulo" name="titulo" required>
                    </div>

                    <div>
                        <label class="form-label" for="email">Correo electrónico</label>
                        <input class="form-input" type="email" id="email" name="email" required>
                    </div>

                    <div>
                        <label class="form-label" for="password">Contraseña</label>
                        <input class="form-input" type="password" id="password" name="password" required>
                    </div>

                    <div>
                        <label class="form-label" for="password2">Repetir contraseña</label>
                        <input class="form-input" type="password" id="password2" name="password2" required>
                    </div>

                    
                    <div class="checkbox-row">
                        <input type="checkbox" id="condiciones" name="condiciones" required>
                        <label for="condiciones">Acepto los términos y condiciones</label>
                    </div>

                    
                    <div class="captcha-wrap">
                        <div class="cf-turnstile" data-sitekey="0x4AAAAAACT39mb_TupAZlv2"></div>
                    </div>

                    <button class="btn-primary" type="submit">Registrarse</button>

                    
                    <div class="form-links">
                        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
                    </div>

                </form>

                
                <script src="../AUTH/comprobarRegistro.js"></script>

            </div>
        </section>
    </main>
<?php
}else{
    header("Location:index.php?error=login_required");
}
include "../INCLUDES/footer.php";
?>