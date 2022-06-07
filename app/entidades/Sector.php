<?php

class Sector
{
    public $id;
    public $nombre;
    public $alta;
    public $ultimaActualizacion;
       
    public function crearRegistro()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO sector (nombra, alta, ultima_actualizacion) 
                                                              VALUES (:nombre)");

        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

}

?>