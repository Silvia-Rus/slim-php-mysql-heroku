<?php

class TipoUsuario
{
    public $id;
    public $nombre;
    public $alta;
    public $ultimaActualizacion;
       
    public function crearRegistro()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO tipo_usuario (tipo) 
                                                                     VALUES (:tipo)");

        $consulta->bindValue(':tipo', $this->nombre, PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

}

?>