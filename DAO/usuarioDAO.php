<?php
require_once("../CONFIG/db.php");
require_once("../CLASSES/usuario.php");
class UsuarioDAO 
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function crear(Usuario $usuario): bool 
    {
        $sql = "INSERT INTO usuarios (nombre_apellido, email, password) VALUES (:nombre, :email, :pass)";
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            'nombre_apellido' => $usuario->getNombreApellidos(),
            'email'  => $usuario->getEmail(),
            'pass'   => $usuario->getPassword()
        ]);
    }

    public function buscarPorEmail(string $email): ?Usuario 
    {
        $sql = "SELECT nombre_apellido, email, password FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            return new Usuario($fila['nombre_apellido'], $fila['email'], $fila['password']);
        }

        return null;
    }
   public function buscarPorNombre(string $nombre): ?Usuario 
{
    $sql = "SELECT nombre_apellido, email, password FROM usuarios WHERE nombre_apellido = :nombre_apellido";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['nombre_apellido' => $nombre]);
    
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fila) {
        return new Usuario($fila['nombre_apellido'], $fila['email'], $fila['password']);
    }

    return null;
}
}
