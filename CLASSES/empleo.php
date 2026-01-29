<?php

class Empleo {
    public string $titulo;
    public string $provincia;
    public string $fecha_publicacion;
    public string $descripcion;
    public string $provincia_alternativa;
    public string $fuente_contenido;
    public string $id_localidad;
    public string $localidad;
    public float $latitud;
    public float $longitud;
    public string $codigo_localidad;
    public int $identificador;
    public string $actualizacion_metadatos;
    public string $enlace_contenido;
    public string $posicion;

    public function __construct(
        string $titulo,
        string $provincia,
        string $descripcion,
        string $codigo_localidad,
        string $enlace_contenido
    ) {
        $this->titulo = $titulo;
        $this->provincia = $provincia; 
        $this->descripcion = $descripcion;
        $this->codigo_localidad = $codigo_localidad;
        $this->enlace_contenido = $enlace_contenido;

        $this->fecha_publicacion = date("Y-m-d");
        $this->provincia_alternativa = "";
        $this->fuente_contenido = "Junta de Castilla y León";
        $this->id_localidad = "";
        $this->localidad = "";
        $this->latitud = 0.0;
        $this->longitud = 0.0;
        $this->posicion = ""; 
        $this->identificador = time(); 
        $this->actualizacion_metadatos = date("Y-m-d");
    }

    public function setDatosGeograficos(string $lat, string $lon, string $nombreLoc, string $nombreProv, string $ine): void {
        $cleanLat = str_replace(',', '.', $lat);
        $cleanLon = str_replace(',', '.', $lon);
        
        $this->latitud = (float) $cleanLat;
        $this->longitud = (float) $cleanLon;
        
        $this->posicion = $cleanLon . $cleanLat;

        $this->localidad = $nombreLoc;
        $this->provincia = $nombreProv;
        $this->id_localidad = $ine;
    }
}