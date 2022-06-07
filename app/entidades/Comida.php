<?php

class Comida
{
    public $id;
    public $idSector;
    public $nombre;
    public $alta;
    public $ultimaActualizacion;
       
    public function crearRegistro()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO comida (nombre, id_sector, alta, ultima_actualizacion) 
                                                              VALUES (:nombre, :idSector, :alta, :ultima_actualizacion)");

        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':id_sector', $this->idSector, PDO::PARAM_STR);
        $consulta->bindValue(':alta', '1', PDO::PARAM_STR);
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':ultima_actualizacion', date_format($fecha, 'Y-m-d H:i:s'));

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function modificarRegistro($registro)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE comida
                                                      SET nombre = :nombre, id_sector = :id_sector
                                                      WHERE id = :id");

        $consulta->bindValue(':nombre', $registro->nombre);
        $consulta->bindValue(':id_sector', $registro->idSector);
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':ultima_actualizacion', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}

?>