<?php
include_once("db/AccesoDatos.php");

class Mesa
{
    public $id;
    public $nombre;
    public $activo;
    public $created_at;
    public $updated_at;

    public static function Alta($mesa)
    {
        return $mesa->crearRegistro();
    }
       
    public function crearRegistro()
    {
        $retorno = null;
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesa (nombre, activo, created_at, updated_at) 
                                                                  VALUES (:nombre, :activo, :created_at, :updated_at)");
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
            $fecha = new DateTime(date("d-m-Y H:i:s"));
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