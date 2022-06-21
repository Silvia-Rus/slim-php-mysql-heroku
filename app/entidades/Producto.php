<?php
include_once("db/AccesoDatos.php");
include_once("entidades/Sector.php");

class Producto
{
    public $id;
    public $id_sector;
    public $nombre;
    public $activo;
    public $created_at;
    public $updated_at;

    public static function Alta($producto)
    {
        $retorno = -1;
        $idDelSector = AccesoDatos::retornarIdPorCampo($producto->id_sector, "nombre", "sector", "Sector");
        $idDelProducto =  AccesoDatos::retornarIdPorCampo($producto->nombre, "nombre", "producto", "Producto");

        if($idDelSector == null)
        {
            $retorno = 0;
        }
        else
        {
            if($idDelProducto != null)
            {               
                $productoAux = AccesoDatos::retornarObjeto($idDelProducto, "producto", "Producto");
                $productoAux->id_sector = $idDelSector;
                $productoAux->precio = $producto->precio;
                // var_dump($productoAux);
                Producto::modificarRegistro($productoAux);
                $retorno = 1;
            }
            else
            {
                $producto->id_sector = $idDelSector;
                $producto->crearRegistro();
                $retorno = 2;
            }
        }
        return $retorno;
    }

    public function crearRegistro()
    {
        $retorno = null;
       try
       {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO producto (nombre, precio, id_sector, activo, created_at, updated_at) 
                                                              VALUES (:nombre, :precio, :id_sector, :activo, :created_at, :updated_at)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':id_sector', $this->id_sector, PDO::PARAM_STR);
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

    public static function modificarRegistro($registro)
    {
        try
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE producto
                                                          SET nombre = :nombre, 
                                                              precio = :precio,
                                                              id_sector = :id_sector,
                                                              updated_at = :updated_at
                                                          WHERE id = :id");  
            $consulta->bindValue(':id', $registro->id);
            $consulta->bindValue(':nombre', $registro->nombre);
            $consulta->bindValue(':precio', $registro->precio);
            $consulta->bindValue(':id_sector', $registro->id_sector);
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

?>