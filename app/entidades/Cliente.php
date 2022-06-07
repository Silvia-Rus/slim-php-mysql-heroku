<?php

class Cliente
{
    public $id;
    public $nombre;
    public $alta;
    public $ultimaActualizacion;
       
    public function crearRegistro()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO cliente (nombre, alta, ultima_actualizacion) 
                                                              VALUES (:nombre)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }
}

?>