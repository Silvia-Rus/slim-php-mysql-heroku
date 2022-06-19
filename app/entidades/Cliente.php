<?php

class Cliente
{
    public $id;
    public $nombre;
    public $created_at;
    public $updated_at;
       
    public function __construct($nombre)
    {
        $this->nombre = $nombre;
    }

    public static function Alta($cliente)
    {
        return $cliente->crearRegistro();
    }
    
    public function crearRegistro()
    {
       $retorno = null;
       try
       {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO cliente (nombre, created_at, updated_at) 
                                                              VALUES (:nombre, :created_at, :updated_at)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':created_at', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':updated_at', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
        $retorno = $objAccesoDatos->obtenerUltimoId();
       }
       catch(Throwable $mensaje)
       {
           printf("Error al conectar en la base de datos: <br> $mensaje .<br>");
       }
       finally
       {
           return $retorno;
       }   
    }
}

?>