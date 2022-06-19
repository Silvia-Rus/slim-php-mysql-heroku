<?php
include_once("db/AccesoDatos.php");

class Pedido
{
    public $id;
    public $id_mesa;
    public $id_cliente;
    public $foto;
    public $estado; //1 - "con cliente esperando pedido” , 2 - ”con cliente comiendo”, 3- “con cliente pagando” y 4- “cerrada”.
    public $created_at;
    public $fecha_prevista;
    public $fecha_fin;
    public $precio_final;
    public $updated_at;
    public $activo;
       
    
    public static function Alta($pedido)
    {
        $retorno = 0;
        $idAux = Pedido::idPedidoAPartirDeMesa($pedido->id_mesa);
        if($idAux == null && $pedido->crearRegistro() != null)
        {
            $retorno = 1;
        }
        return $retorno;
    }

    public function AsignarFechaFin()
    {
        //asignarla
        //modificarBD

    }

    public function CambiarEstado($idEstado)
    {
        //cambiarlo
        //modificarBd
    }

    public function CerrarPedido($idEstado)
    {
        //cambiar el estado a cerrado
        //poner fecha fin
        //calcular el precio
        //modificarBd
    }

    public function CalcularPrecio()
    {

    }

    public static function idPedidoAPartirDeMesa($id)
    {
        $retorno = null;

        try
        {
            $conexion = AccesoDatos::obtenerInstancia();
            $consulta = $conexion->prepararConsulta("SELECT * FROM pedido WHERE id_mesa = $id and estado not like '4';");
            $consulta->execute();
            $resultado = $consulta->fetchObject("Pedido");
            if($resultado != null)
            {
                $retorno = $resultado->id;
            }         
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

    public function crearRegistro() 
    {
        $retorno = null;
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO  pedido (id_mesa, id_cliente, estado, created_at, fecha_prevista, updated_at, activo) 
                                                                  VALUES (:id_mesa, :id_cliente, :estado, :created_at, :fecha_prevista, :updated_at, :activo)");
            $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_STR);
            $consulta->bindValue(':id_cliente', $this->id_cliente, PDO::PARAM_STR); 
            $consulta->bindValue(':estado', '1', PDO::PARAM_STR); 
            $consulta->bindValue(':activo', '1', PDO::PARAM_STR);
            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $consulta->bindValue(':created_at', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->bindValue(':updated_at', date_format($fecha, 'Y-m-d H:i:s'));
            $fecha_prevista = $fecha->modify('+'.$this->fecha_prevista.' minutes');
            $consulta->bindValue(':fecha_prevista', date_format($fecha_prevista,'Y-m-d H:i:s'), PDO::PARAM_STR);
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