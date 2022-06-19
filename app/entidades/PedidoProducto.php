<?php
include_once("db/AccesoDatos.php");

class PedidoProducto
{
    public $id;
    public $id_pedido;
    public $id_producto;
    public $cantidad;
    public $estado; //0 - Pendiente // 1 - En preparación // 2 - Listo 
    public $fecha_prevista;
    public $fecha_fin;
    public $updated_at;
    public $created_at;
    public $activo;

    public static function Alta($pedidoProducto)
    {
        $id_pedidoAux = Pedido::idPedidoAPartirDeMesa($pedidoProducto->id_pedido); 
        
        $retorno = 0;
        if($id_pedidoAux != null)
        {
            $pedidoProducto->id_pedido = $id_pedidoAux;
            $pedidoProducto->crearRegistro();
            $retorno = 1;
        }
        return $retorno;
    }

    public function AsignarFechaPrevista($tardanzaEnMinutos)
    {
        //calcular
        //modificar en BD
    }

    public function CambiarEstado($estado)
    {
        //cambiar el estado
        //modificar en BD
    }

    public function ListarPorSector($sector)
    {

    }


    public function crearRegistro() 
    {
        $retorno = null;
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO  pedido_producto (id_pedido, id_producto, cantidad, estado, created_at, updated_at, activo) 
                                                                  VALUES (:id_pedido, :id_producto, :cantidad, :estado, :created_at, :updated_at, :activo)");
            $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_STR);
            $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_STR); 
            $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_STR); 
            $consulta->bindValue(':estado', '0', PDO::PARAM_STR); 
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