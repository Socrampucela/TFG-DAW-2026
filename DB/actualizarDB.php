<?php
require_once "../CONFIG/db.php";
set_time_limit(120); //POR SI ACASO NO DA TIEMPO A CONECTAR Y DESCARGAR
$apiUrl = "https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/ofertas-de-empleo/records?limit=100&order_by=fecha_publicacion%20DESC";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-Data-Sync-JCYL'); 
$response = curl_exec($ch);
curl_close($ch);

$datos = json_decode($response, true);

if (isset($datos['results'])) {
    $nuevos = 0;
    $actualizados = 0;

    foreach ($datos['results'] as $record) {
        $id_api = $record['identificador']; 

        $check = $conn->prepare("SELECT COUNT(*) FROM ofertas_de_empleo WHERE Identificador = ?");
        $check->execute([$id_api]);
        $existe = $check->fetchColumn() > 0;

        // Formatear Posicion como "latitud, longitud"
        $posicionString = null;
        if (isset($record['posicion']['lat']) && isset($record['posicion']['lon'])) {
            $posicionString = $record['posicion']['lat'] . ", " . $record['posicion']['lon'];
        }

        // Mapeo corregido basándose en la estructura real del JSON de la JCyL
        $params = [
            $record['titulo'] ?? null,
            $record['provincia'] ?? null,
            $record['fecha_publicacion'] ?? null,
            $record['descripcion'] ?? null,
            $record['provinciaalternativa'] ?? "", // Campo JCyL: provincia_alternativa
            $record['fuentecontenido'] ?? null,   // Campo JCyL: fuente_del_contenido
            $record['codigo_localidad'] ?? null,          // Campo JCyL: id_localidad
            $record['localidad'] ?? null,
            $record['latitud'] ?? null,
            $record['longitud'] ?? null,
            $record['codigo_localidad'] ?? null,
            $record['actualizacionmetadatos'] ?? null, // Campo JCyL: actualizacion_de_metadatos
            $record['enlace_al_contenido'] ?? null,
            $posicionString, 
            $id_api
        ];

        if ($existe) {
            $sql = "UPDATE ofertas_de_empleo SET 
                    Título = ?, Provincia = ?, `Fecha publicación` = ?, Descripción = ?, 
                    ProvinciaAlternativa = ?, FuenteContenido = ?, `ID Localidad` = ?, 
                    Localidad = ?, Latitud = ?, Longitud = ?, `Código localidad` = ?, 
                    `actualizacion de metadatos` = ?, `Enlace al contenido` = ?, 
                    Posicion = ? 
                    WHERE Identificador = ?";
        } else {
            $sql = "INSERT INTO ofertas_de_empleo (
                        Título, Provincia, `Fecha publicación`, Descripción, 
                        ProvinciaAlternativa, FuenteContenido, `ID Localidad`, 
                        Localidad, Latitud, Longitud, `Código localidad`, 
                        `actualizacion de metadatos`, `Enlace al contenido`, 
                        Posicion, Identificador
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        if ($existe) $actualizados++; else $nuevos++;
    }
    
    echo "Sincronización finalizada: $nuevos nuevos, $actualizados actualizados.";
} else {
    echo "Error en la respuesta de la API.";
}