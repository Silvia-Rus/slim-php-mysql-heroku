<?php
class AccesoDatos
{
    private static $objAccesoDatos;
    private $objetoPDO;

    private function __construct()
    {
        try {
            $this->objetoPDO = new PDO('mysql:host='.$_ENV['MYSQL_HOST'].';dbname='.$_ENV['MYSQL_DB'].';charset=utf8', $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASS'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            //$this->objetoPDO = new PDO("mysql:host=127.0.0.1:3306;dbname=comandaApp", 'root', '', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
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
        $consulta = "SELECT * FROM $tabla WHERE $tabla.$campo = '$valor'";
        //var_dump($consulta);
        $retorno = null;
        try
        {
            $conexion = AccesoDatos::obtenerInstancia();
            $consulta = $conexion->prepararConsulta($consulta);
            $consulta->execute();
            $resultado = $consulta->fetchObject($clase);
            if($resultado != null)
            {
                $retorno = $resultado->id;          
            }
            /*$resultado = $consulta->fetchAll();
            if(sizeof($resultado) > 0)
            {
                $retorno = $resultado[0]["id"];
            }*/
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

    public static function ObtenerConsulta($sql)
    {
        try
        {
            $conexion = AccesoDatos::obtenerInstancia();
            $consulta = $conexion->prepararConsulta($sql);
            $consulta->execute();
            $retorno = $consulta->fetchAll();
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

    public static function ObtenerPedidosPorSector($sector)
    {
        $sql = "SELECT  pp.id,
                        pp.id_pedido AS pedido, 
                        pr.nombre AS producto, 
                        pp.cantidad AS cantidad, 
                        CASE 
                        WHEN pp.estado = 0 THEN 'Pendiente' 
                        WHEN pp.estado = 1 THEN 'En preparación' 
                        WHEN pp.estado = 2 THEN 'Listo' 
                        ELSE 'Error' end
                        as Estado 
                FROM pedido_producto pp 
                    LEFT JOIN producto pr ON pp.id_producto = pr.id
                    LEFT JOIN sector s ON pr.id_sector = s.id
                WHERE s.id = $sector and pp.estado < 3
                ORDER BY pp.id_pedido, pp.created_at;";
         
        return AccesoDatos::ObtenerConsulta($sql);
        /*try
        {
            $conexion = AccesoDatos::obtenerInstancia();
            $consulta = $conexion->prepararConsulta("SELECT pp.id,
                                                            pp.id_pedido AS pedido, 
                                                            pr.nombre AS producto, 
                                                            pp.cantidad AS cantidad, 
                                                            CASE 
                                                            WHEN pp.estado = 0 THEN 'Pendiente' 
                                                            WHEN pp.estado = 1 THEN 'En preparación' 
                                                            WHEN pp.estado = 2 THEN 'Listo' 
                                                            ELSE 'Error' end
                                                            as Estado 
                                                    FROM pedido_producto pp 
                                                        LEFT JOIN producto pr ON pp.id_producto = pr.id
                                                        LEFT JOIN sector s ON pr.id_sector = s.id
                                                    WHERE s.id = $sector and pp.estado < 3
                                                    ORDER BY pp.id_pedido, pp.created_at;");
            $consulta->execute();
            $retorno = $consulta->fetchAll(PDO::FETCH_CLASS);
        }
        catch(Throwable $mensaje)
        {
            printf("Error al buscar en la base de datos: <br> $mensaje .<br>");
        }
        finally
        {
            return $retorno;
        }*/
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

    public static function ImprimirTabla($tabla, $clase)
    {           
        $retorno = null;
        try
        {
            $retorno = array();
            $conexion = AccesoDatos::obtenerInstancia();
            $retorno = $conexion->obtenerTodos($tabla, $clase);
            //$retorno = AccesoDatos::ImprimirArray($resultado);
        }
        catch (Throwable $mensaje)
        {
            printf("Error al consultar la base de datos: <br> $mensaje .<br>");
            die();
        }
        finally
        {
            return $retorno;
        }  
    }

    /*public static function ImprimirArray($array)
    {
        if(sizeof($array) == 0 || $array == null)
        {
            print "\t<td>Sin datos disponibles.</td>\n";
            print "</tr>\n";
        }
        else
        { 
            foreach ($array as $fila) 
            {
                foreach ($fila as $columna) 
                {
                    if($columna == null)
                    {
                        print "\t<td>Sin datos disponibles.</td>\n";
                    }
                    else
                    {
                        print "\t<td>$columna</td>\n";
                    }
                    print "\t<td>$columna</td>\n";
                }
                print "</tr>\n";
            } 
        }       
    }*/
}
