<?php

class Sector
{
    public $id;
    public $nombre;
    public $activo;
    public $created_at;
    public $updated_at;
       
    public function crearRegistro()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO sector (nombra, activo, created_at) 
                                                              VALUES (:nombre, :activo, :created_at)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':created_at', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

}

?>