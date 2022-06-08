<?php

class Comida
{
    public $id;
    public $idSector;
    public $nombre;
    public $activo;
    public $created_at;
    public $updated_at;
       
    public function crearRegistro()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO comida (nombre, id_sector, activo, created_at) 
                                                              VALUES (:nombre, :idSector, :activo, :created_at)");

        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':id_sector', $this->idSector, PDO::PARAM_STR);
        $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':created_at', date_format($fecha, 'Y-m-d H:i:s'));

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function modificarRegistro($registro)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE comida
                                                      SET nombre = :nombre, id_sector = :id_sector, updated_at = :updated_at
                                                      WHERE id = :id");

        $consulta->bindValue(':nombre', $registro->nombre);
        $consulta->bindValue(':id_sector', $registro->idSector);
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':updated_at', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}

?>