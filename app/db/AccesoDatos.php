<?php
class AccesoDatos
{
    private static $objAccesoDatos;
    private $objetoPDO;

    private function __construct()
    {
        try {
            $this->objetoPDO = new PDO('mysql:host='.$_ENV['MYSQL_HOST'].';dbname='.$_ENV['MYSQL_DB'].';charset=utf8', $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASS'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->objetoPDO->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
            die();
        }
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new AccesoDatos();
        }
        return self::$objAccesoDatos;
    }

    public function prepararConsulta($sql)
    {
        return $this->objetoPDO->prepare($sql);
    }

    public function obtenerUltimoId()
    {
        return $this->objetoPDO->lastInsertId();
    }

    public function __clone()
    {
        trigger_error('ERROR: La clonación de este objeto no está permitida', E_USER_ERROR);
    }

    public static function borrarRegistro($id, $tabla)
    {
        $retorno = false;
        try
        {   
            if($id != null)
            {
                $conexion = AccesoDatos::obtenerInstancia();
                $consulta = $conexion->prepaparConsulta("UPDATE $tabla 
                                                         SET activo = :activo, updated_at = :updated_at
                                                         WHERE id = :id");
                $fecha = new DateTime(date("d-m-Y"));
                $consulta->bindValue(':updated_at', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->bindValue(':activo', '0');
                $consulta->execute();
                $retorno = true;
            }
        }
        catch(Throwable $mensaje)
        {
            printf("Error al borrar en la base de datos: <br> $mensaje .<br>");
        }
        finally
        {
            return $retorno;
        }
    }

    public static function retornarObjeto($id, $tabla, $clase)
    {
        $retorno = null;
        try
        {
            $conexion = AccesoDatos::obtenerInstancia();
            $consulta = $conexion->prepararConsulta("SELECT * FROM $tabla WHERE $id = $tabla.id");
            $consulta->execute();
            $resultado = $consulta->fetchObject($clase);
            $retorno = $resultado;          
        }
        catch(Throwable $mensaje)
        {
            printf("Error al buscar en la base de datos: <br> $mensaje .<br>");
        }
        finally
        {
            return $retorno;
        }
    }

    public static function retornarIdPorCampo($valor, $campo, $tabla, $clase)
    {
        $retorno = null;
        try
        {
            $conexion = AccesoDatos::obtenerInstancia();
            $consulta = $conexion->prepararConsulta("SELECT * FROM $tabla WHERE $valor = $tabla.$campo");
            $consulta->execute();
            $resultado = $consulta->fetchObject($clase);
            $retorno = $resultado->id;          
        }
        catch(Throwable $mensaje)
        {
            printf("Error al buscar en la base de datos: <br> $mensaje .<br>");
        }
        finally
        {
            return $retorno;
        }
    }

    public static function obtenerTodos($tabla, $clase)
    {
        $retorno = null;
        try
        {
            $conexion = AccesoDatos::obtenerInstancia();
            $consulta = $conexion->prepararConsulta("SELECT * FROM $tabla");
            $consulta->execute();
            $retorno = $consulta->fetchAll(PDO::FETCH_CLASS, $clase);
        }
        catch(Throwable $mensaje)
        {
            printf("Error al buscar en la base de datos: <br> $mensaje .<br>");
        }
        finally
        {
            return $retorno;
        }   
    }

    public static function modificarCampo($id, $tabla, $campo, $valor)
    {
        $retorno = false;
        try
        {   
            if($id != null && $campo != 'id')
            {
                $conexion = AccesoDatos::obtenerInstancia();
                $consulta = $conexion->prepaparConsulta("UPDATE $tabla 
                                                         SET $campo = $valor, updated_at = :updated_at 
                                                         WHERE id = $id");
                $fecha = new DateTime(date("d-m-Y"));
                $consulta->bindValue(':updated_at', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->execute();
                $retorno = true;
            }
        }
        catch(Throwable $mensaje)
        {
            printf("Error al borrar en la base de datos: <br> $mensaje .<br>");
        }
        finally
        {
            return $retorno;
        }
    }
}
