<?php

class UsuarioDAO {
    private PDO $conn;

    public function __construct(PDO $conexion) {
        $this->conn = $conexion;
    }

    // Método para convertir array a objeto Usuario
    private function arrayAObjeto(array $datos): Usuario {
        $usuario = new Usuario(
            $datos['nombre_apellido'],
            $datos['email'],
            $datos['password'],
            $datos['rol'] ?? 'usuario'
        );
        
        // Asignar ID y fecha si existen
        if (isset($datos['id'])) {
            $usuario->id = $datos['id'];
        }
        if (isset($datos['fecha_registro'])) {
            $usuario->fecha_registro = $datos['fecha_registro'];
        }
        
        return $usuario;
    }

    // Obtener todos los usuarios (paginado) - DEVUELVE ARRAYS para vistas admin
    public function obtenerTodosPaginado(int $inicio, int $cantidad): array {
        try {
            $sql = "SELECT id, nombre_apellido, email, rol, fecha_registro 
                    FROM usuarios 
                    ORDER BY fecha_registro DESC 
                    LIMIT :inicio, :cantidad";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodosPaginado: " . $e->getMessage());
            return [];
        }
    }

    // Contar total de usuarios
    public function contarTodos(): int {
        try {
            $res = $this->conn->query("SELECT COUNT(*) FROM usuarios");
            return (int)$res->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    // Obtener usuario por ID - DEVUELVE ARRAY para vistas admin
    public function obtenerPorId(int $id): ?array {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ?: null;
        } catch (PDOException $e) {
            error_log("Error en obtenerPorId: " . $e->getMessage());
            return null;
        }
    }

    // Buscar usuario por email (para login) - DEVUELVE OBJETO Usuario
    public function buscarPorEmail(string $email): ?Usuario {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado) {
                return $this->arrayAObjeto($resultado);
            }
            
            return null;
        } catch (PDOException $e) {
            error_log("Error en buscarPorEmail: " . $e->getMessage());
            return null;
        }
    }

    // Crear usuario
    public function crear(Usuario $usuario): bool {
        try {
            $sql = "INSERT INTO usuarios (nombre_apellido, email, password, rol, fecha_registro) 
                    VALUES (:nombre, :email, :password, :rol, :fecha)";
            
            $stmt = $this->conn->prepare($sql);
            
            return $stmt->execute([
                ':nombre' => $usuario->nombre_apellido,
                ':email' => $usuario->email,
                ':password' => $usuario->password,
                ':rol' => $usuario->rol,
                ':fecha' => $usuario->fecha_registro
            ]);
        } catch (PDOException $e) {
            error_log("Error en crear usuario: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar usuario
    public function actualizar(int $id, array $datos): bool {
        try {
            $campos = [];
            $params = [':id' => $id];

            if (isset($datos['nombre_apellido'])) {
                $campos[] = "nombre_apellido = :nombre";
                $params[':nombre'] = $datos['nombre_apellido'];
            }

            if (isset($datos['email'])) {
                $campos[] = "email = :email";
                $params[':email'] = $datos['email'];
            }

            if (isset($datos['rol'])) {
                $campos[] = "rol = :rol";
                $params[':rol'] = $datos['rol'];
            }

            if (isset($datos['password']) && !empty($datos['password'])) {
                $campos[] = "password = :password";
                $params[':password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
            }

            if (empty($campos)) {
                return false;
            }

            $sql = "UPDATE usuarios SET " . implode(', ', $campos) . " WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error en actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar usuario
    public function eliminar(int $id): bool {
        try {
            $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error en eliminar usuario: " . $e->getMessage());
            return false;
        }
    }

    // Verificar si un email ya está registrado
    public function emailExiste(string $email, int $excluirId = 0): bool {
        try {
            if ($excluirId > 0) {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ? AND id != ?");
                $stmt->execute([$email, $excluirId]);
            } else {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
            }
            return (int)$stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Obtener estadísticas
    public function obtenerEstadisticas(): array {
        try {
            $stats = [];

            // Total de usuarios
            $stmt = $this->conn->query("SELECT COUNT(*) FROM usuarios");
            $stats['total_usuarios'] = (int)$stmt->fetchColumn();

            // Usuarios por rol
            $stmt = $this->conn->query("SELECT rol, COUNT(*) as total FROM usuarios GROUP BY rol");
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $stats['usuarios_normales'] = 0;
            $stats['administradores'] = 0;
            
            foreach ($roles as $rol) {
                if ($rol['rol'] === 'usuario') {
                    $stats['usuarios_normales'] = (int)$rol['total'];
                } else {
                    $stats['administradores'] = (int)$rol['total'];
                }
            }

            // Usuarios registrados este mes
            $stmt = $this->conn->query("SELECT COUNT(*) FROM usuarios WHERE MONTH(fecha_registro) = MONTH(CURDATE()) AND YEAR(fecha_registro) = YEAR(CURDATE())");
            $stats['registros_mes'] = (int)$stmt->fetchColumn();

            return $stats;
        } catch (PDOException $e) {
            error_log("Error en obtenerEstadisticas: " . $e->getMessage());
            return [
                'total_usuarios' => 0,
                'usuarios_normales' => 0,
                'administradores' => 0,
                'registros_mes' => 0
            ];
        }
    }
}