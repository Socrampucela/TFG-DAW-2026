<?php

class EmpleoDAO {
    private PDO $conn;

    public function __construct(PDO $conexion) {
        $this->conn = $conexion;
    }


    public function obtenerEmpleosPaginado(int $inicio, int $cantidad): array {
        try {
            $sql = "SELECT * FROM `ofertas_de_empleo` 
                    ORDER BY STR_TO_DATE(`Fecha publicación`, '%Y-%m-%d') DESC 
                    LIMIT :inicio, :cantidad";
            
            $sentencia = $this->conn->prepare($sql);
            $sentencia->bindValue(':inicio', $inicio, PDO::PARAM_INT);
            $sentencia->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
            $sentencia->execute();
            
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerEmpleosPaginado: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerTodos(): array{
        try{
            $sentencia = "SELECT * FROM `ofertas_de_empleo`";
            $sentencia = $this->conn->prepare($sentencia);
            $sentencia->execute();
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function contarTodos(): int {
        try {
            $res = $this->conn->query("SELECT COUNT(*) FROM `ofertas_de_empleo`");
            return (int)$res->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }


    public function insertar(Empleo $empleo): bool {
        try {

            $sentenciaMun = $this->conn->prepare("SELECT Municipio, Provincia, Latitud, Longitud, Cod_INE 
                                            FROM municipiosjcyl 
                                            WHERE Cod_Municipio = :codmun AND Cod_Provincia = :codprov");

            $sentenciaMun->bindValue(':codmun', $empleo->codigo_localidad, PDO::PARAM_STR);
            $sentenciaMun->bindValue(':codprov', $empleo->provincia, PDO::PARAM_STR);
            $sentenciaMun->execute();
            
            $res = $sentenciaMun->fetch(PDO::FETCH_ASSOC);

            if ($res) {
                $empleo->setDatosGeograficos(
                    (string)$res['Latitud'], 
                    (string)$res['Longitud'], 
                    (string)$res['Municipio'], 
                    (string)$res['Provincia'], 
                    (string)$res['Cod_INE']
                );
            } else {
                error_log("No se encontró municipio con Cod_Municipio={$empleo->codigo_localidad} y Cod_Provincia={$empleo->provincia}");
            }

            $sql = "INSERT INTO ofertas_de_empleo (
                `Título`, 
                `Provincia`, 
                `Fecha publicación`, 
                `Descripción`, 
                `ProvinciaAlternativa`, 
                `FuenteContenido`, 
                `ID Localidad`, 
                `Localidad`, 
                `Latitud`, 
                `Longitud`, 
                `Código localidad`, 
                `Identificador`, 
                `actualizacion de metadatos`, 
                `Enlace al contenido`, 
                `Posicion`
            ) VALUES (
                :tit, :prov, :fec, :des, :palt, :fuen, :idloc, :loc, :lat, :lon, :codloc, :ident, :meta, :link, :pos
            )";

            $sentencia = $this->conn->prepare($sql);

            return $sentencia->execute([
                ':tit'    => $empleo->titulo,
                ':prov'   => $empleo->provincia,
                ':fec'    => $empleo->fecha_publicacion,
                ':des'    => $empleo->descripcion,
                ':palt'   => $empleo->provincia_alternativa,
                ':fuen'   => $empleo->fuente_contenido,
                ':idloc'  => $empleo->id_localidad,
                ':loc'    => $empleo->localidad,
                ':lat'    => $empleo->latitud,
                ':lon'    => $empleo->longitud,
                ':codloc' => $empleo->codigo_localidad,
                ':ident'  => $empleo->identificador,
                ':meta'   => $empleo->actualizacion_metadatos,
                ':link'   => $empleo->enlace_contenido,
                ':pos'    => $empleo->posicion
            ]);

        } catch (PDOException $e) {
            error_log("Error crítico en EmpleoDAO::insertar -> " . $e->getMessage());
            return false;
        }
    }
public function buscarEmpleosPaginado(array $filtros, int $inicio, int $cantidad): array
    {
        $where = [];
        $params = [];

        if (!empty($filtros['titulo'])) {
            $where[] = "`Título` LIKE :titulo";
            $params[':titulo'] = '%' . $filtros['titulo'] . '%';
        }

        if (!empty($filtros['provincia'])) {
            $where[] = "`Provincia` = :provincia";
            $params[':provincia'] = $filtros['provincia'];
        }

        if (!empty($filtros['localidad'])) {
            $where[] = "`Localidad` = :localidad";
            $params[':localidad'] = $filtros['localidad'];
        }

        $dias = (int)($filtros['dias'] ?? 0);
        if ($dias > 0) {
            $where[] = "STR_TO_DATE(`Fecha publicación`, '%Y-%m-%d') >= DATE_SUB(CURDATE(), INTERVAL $dias DAY)";
        }

        $sql = "SELECT *
                FROM `ofertas_de_empleo`";

        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY STR_TO_DATE(`Fecha publicación`, '%Y-%m-%d') DESC
                  LIMIT :inicio, :cantidad";

        $sentencia = $this->conn->prepare($sql);

        foreach ($params as $k => $v) {
            $sentencia->bindValue($k, $v, PDO::PARAM_STR);
        }

        $sentencia->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $sentencia->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);

        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarFiltrados(array $filtros): int
    {
        $where = [];
        $params = [];

        if (!empty($filtros['titulo'])) {
            $where[] = "`Título` LIKE :titulo";
            $params[':titulo'] = '%' . $filtros['titulo'] . '%';
        }

        if (!empty($filtros['provincia'])) {
            $where[] = "`Provincia` = :provincia";
            $params[':provincia'] = $filtros['provincia'];
        }

        if (!empty($filtros['localidad'])) {
            $where[] = "`Localidad` = :localidad";
            $params[':localidad'] = $filtros['localidad'];
        }

        $dias = (int)($filtros['dias'] ?? 0);
        if ($dias > 0) {
            $where[] = "STR_TO_DATE(`Fecha publicación`, '%Y-%m-%d') >= DATE_SUB(CURDATE(), INTERVAL $dias DAY)";
        }

        $sql = "SELECT COUNT(*)
                FROM `ofertas_de_empleo`";

        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sentencia = $this->conn->prepare($sql);

        foreach ($params as $k => $v) {
            $sentencia->bindValue($k, $v, PDO::PARAM_STR);
        }

        $sentencia->execute();
        return (int)$sentencia->fetchColumn();
    }

    public function devolverEmpleoProvincia(){
        try{
            $sentencia = "SELECT provincia, COUNT(*) AS total FROM ofertas_de_empleo GROUP BY provincia ORDER BY total DESC";
            $sentencia = $this->conn->prepare($sentencia);
            $sentencia->execute();
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error en devolverEmpleoProvincia: " . $e->getMessage());
        }
    }
    
}