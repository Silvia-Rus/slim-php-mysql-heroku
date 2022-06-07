<?php

class Mesa
{
    public $id;
    public $nombre;
    public $alta;
    public $ultimaActualizacion;
       
    public function crearRegistro()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesa (nombre, alta, ultima_actualizacion) 
                                                              VALUES (:nombre)");

        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    
}

?>