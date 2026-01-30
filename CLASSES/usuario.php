<?php

class Usuario {
    public int $id;
    public string $nombre_apellido;
    public string $email;
    public string $password;
    public string $rol; // 'usuario' o 'administrador'
    public string $fecha_registro;

    public function __construct(
        string $nombre_apellido,
        string $email,
        string $password,
        string $rol = 'usuario'
    ) {
        $this->nombre_apellido = $nombre_apellido;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
        $this->fecha_registro = date("Y-m-d H:i:s");
    }

    // Getters para compatibilidad con tu código existente
    public function getID(): int {
        return $this->id;
    }

    public function getNombreApellidos(): string {
        return $this->nombre_apellido;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRol(): string {
        return $this->rol;
    }

    public function getFechaRegistro(): string {
        return $this->fecha_registro;
    }

    // Métodos útiles
    public function esAdmin(): bool {
        return $this->rol === 'administrador';
    }

    public function hashPassword(): void {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    }
}