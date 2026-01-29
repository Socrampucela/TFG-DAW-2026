<?php
class empleo
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // ======== INDEX (sin filtros) ========

    public function obtenerEmpleosPaginado($inicio, $cantidad)
    {
        $sql = "SELECT *
                FROM `ofertas_de_empleo`
                ORDER BY STR_TO_DATE(`Fecha publicación`, '%Y-%m-%d') DESC
                LIMIT :inicio, :cantidad";

        $sentencia = $this->conn->prepare($sql);
        $sentencia->bindValue(':inicio', (int)$inicio, PDO::PARAM_INT);
        $sentencia->bindValue(':cantidad', (int)$cantidad, PDO::PARAM_INT);
        $sentencia->execute();

        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarTodos()
    {
        $sql = "SELECT COUNT(*) FROM `ofertas_de_empleo`";
        $res = $this->conn->query($sql);
        return (int)$res->fetchColumn();
    }

    // ======== BUSCADOR (con filtros) ========

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
            // Como viene de select (texto), puede ser exacto
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

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }

        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }

        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}
?>
