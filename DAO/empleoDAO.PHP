<?php
class empleo 
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }  
    public function obtenerEmpleosPaginado($inicio,$cantidad){
        $sql = "SELECT * FROM `ofertas_de_empleo` ORDER BY `Fecha publicación` DESC LIMIT :inicio, :cantidad ";
        $sentencia = $this->conn->prepare($sql);

        $sentencia->bindValue(':inicio', (int)$inicio, PDO::PARAM_INT);
        $sentencia->bindValue(':cantidad', (int)$cantidad, PDO::PARAM_INT);

        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }
    public function contarTodos(){
        $sql = "SELECT COUNT(*) FROM `ofertas_de_empleo`";
        $res = $this->conn->query($sql);
        return (int)$res->fetchColumn();
    }
}

?>