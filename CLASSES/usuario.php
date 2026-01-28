<?php

class Usuario 
{
    private ?int $id;
    private string $nombreApellidos;
    private string $email;
    private string $password;

    public function __construct(string $nombreApellidos, string $email, string $password)
    {
        $this->id = $id;
        $this->nombreApellidos = $nombreApellidos;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): ?int 
    {
        return $this->id;
    }

    public function setId(int $id): void 
    {
        $this->id = $id;
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