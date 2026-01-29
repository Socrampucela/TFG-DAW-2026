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
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerEmpleosPaginado: " . $e->getMessage());
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

            $stmtMun = $this->conn->prepare("SELECT Municipio, Provincia, Latitud, Longitud, Cod_INE 
                                            FROM municipiosjcyl 
                                            WHERE Cod_Municipio = ? AND Cod_Provincia = ?");

            $stmtMun->execute([$empleo->codigo_localidad, $empleo->provincia]);
            $res = $stmtMun->fetch(PDO::FETCH_ASSOC);

            if ($res) {

                $empleo->setDatosGeograficos(
                    (string)$res['Latitud'], 
                    (string)$res['Longitud'], 
                    (string)$res['Municipio'], 
                    (string)$res['Provincia'], 
                    (string)$res['Cod_INE']
                );
            }

            // 2. INSERTAR EN LA TABLA FINAL
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

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
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
}