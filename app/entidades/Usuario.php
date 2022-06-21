<?php
include_once("db/AccesoDatos.php");
include_once("entidades/TipoUsuario.php");

class Usuario
{
    public $id;
    public $dni;
    public $clave;
    public $tipo;
    public $activo;
    public $created_at;
    public $updated_at;
    
    /*public function __construct($dni, $clave, $tipo)
    {
        $this->dni = $dni;
        $this->clave = $clave;
        $this->tipo = $tipo;
    }*/

    public static function Alta($usuario)
    {
        $retorno = -1;
        $idDelTipo = AccesoDatos::retornarIdPorCampo($usuario->tipo, "nombre", "tipo_usuario", "TipoUsuario");
        $idDelUsuario =  AccesoDatos::retornarIdPorCampo($usuario->dni, "dni", "usuario", "Usuario");

        if($idDelTipo == null)
        {
            $retorno = 0;
        }
        else
        {
            if($idDelUsuario != null)
            {
                $usuarioAux = AccesoDatos::retornarObjeto($idDelUsuario, "usuario", "Usuario");
                $usuarioAux->tipo = $idDelTipo;
                Usuario::modificarRegistro($usuarioAux);
                $retorno = 1;
            }
            else
            {
                $usuario->tipo = $idDelTipo;
                //var_dump($usuario->tipo);
                $usuario->crearRegistro();
                $retorno = 2;
            }
        }
        return $retorno;
    }

    public static function Login($dni, $clave)
    {
        $retorno = null;
        $idDni = AccesoDatos::retornarIdPorCampo($dni, 'dni', 'usuario', 'Usuario');
        if($idDni != null)
        {
            $usuario = AccesoDatos::retornarObjeto($idDni, 'usuario', 'Usuario');
            if($clave == $usuario->clave)
            {
                $retorno = $usuario;
            }
        }
        return $retorno;
    }
    //Manejo BD
    public function crearRegistro()
    {
        $retorno = null;
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuario (dni, clave, tipo, activo, created_at, updated_at) 
                                                                         VALUES (:dni, :clave, :tipo, :activo, :created_at, :updated_at) ");
            //$claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
            $consulta->bindValue(':dni', $this->dni, PDO::PARAM_STR);
            //$consulta->bindValue(':clave', $claveHash);
            $consulta->bindValue(':clave', $this->clave);
            $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $consulta->bindValue(':created_at', date_format($fecha, 'Y-m-d H:i:s')); //POR QUÉ NO GRABA LA HORA?
            $consulta->bindValue(':updated_at', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->execute();
            $retorno =  $objAccesoDatos->obtenerUltimoId();
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

    public static function modificarRegistro($usuario)
    {       
        try
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE usuario
                                                          SET dni = :dni, 
                                                              clave = :clave, 
                                                              tipo = :tipo, 
                                                              activo = :activo,
                                                              updated_at = :updated_at
                                                          WHERE id = :id");
            $claveHash = password_hash($usuario->clave, PASSWORD_DEFAULT);
            $consulta->bindValue(':id', $usuario->id, PDO::PARAM_STR);
            $consulta->bindValue(':dni', $usuario->dni, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $claveHash);
            $consulta->bindValue(':tipo', $usuario->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $consulta->bindValue(':updated_at', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->execute();
        }
        catch(Throwable $mensaje)
        {
            printf("Error al conectar en la base de datos: <br> $mensaje .<br>");
        }
    }
}