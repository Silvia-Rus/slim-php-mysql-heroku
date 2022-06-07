<?php

class Usuario
{
    public $id;
    public $dni;
    public $clave;
    public $tipo;
    public $alta;
    public $ultimaActualizacion;

    //Manejo BD

    public function crearRegistro()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (dni, clave, tipo, alta, ultima_actualizacion) 
                                                                     VALUES (:dni, :clave, :tipo, :alta, :ultima_actualizacion) ");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':dni', $this->dni, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':dni', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':alta', '1', PDO::PARAM_STR);
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':ultima_actualizacion', date_format($fecha, 'Y-m-d H:i:s'));

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function modificarRegistro($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios 
                                                      SET dni = :dni, 
                                                          clave = :clave, 
                                                          tipo = :tipo, 
                                                          ultima_actualizacion = :ultima_actualizacion
                                                      WHERE id = :id");
        $claveHash = password_hash($usuario->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':dni', $usuario->sector, PDO::PARAM_STR);
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':ultima_actualizacion', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}