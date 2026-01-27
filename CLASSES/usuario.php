<?php

class Usuario 
{
    private string $nombreApellidos;
    private string $email;
    private string $password;

    public function __construct(string $nombreApellidos, string $email, string $password)
    {
        $this->nombreApellidos = $nombreApellidos;
        $this->email = $email;
        $this->password = $password;
    }

    public function getNombreApellidos(): string 
    {
        return $this->nombreApellidos;
    }

    public function setNombreApellidos(string $nombreApellidos): void 
    {
        $this->nombreApellidos = $nombreApellidos;
    }

    public function getEmail(): string 
    {
        return $this->email;
    }

    public function setEmail(string $email): void 
    {
        $this->email = $email;
    }

    public function getPassword(): string 
    {
        return $this->password;
    }

    public function setPassword(string $password): void 
    {
        $this->password = $password;
    }
}