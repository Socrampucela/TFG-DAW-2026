<?php

function estaLogueado(): bool {
    return isset($_SESSION['nombre']) && isset($_SESSION['usuario_id']);
}

function esAdmin(): bool {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';
}


function requiereLogin(string $redirect = '../VIEWS/login.php'): void {
    if (!estaLogueado()) {
        header("Location: $redirect");
        exit();
    }
}

function requiereAdmin(string $redirect = '../VIEWS/index.php'): void {
    if (!esAdmin()) {
        header("Location: $redirect?error=acceso_denegado");
        exit();
    }
}


function verificarAcceso(bool $soloAdmin = false): void {
    requiereLogin();
    if ($soloAdmin) {
        requiereAdmin();
    }
}