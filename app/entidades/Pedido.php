<?php

class Pedido
{
    public $id;
    public $idMesa;
    public $idCliente;
    public $idUsuario;
    public $tragos;
    public $cervezas;
    public $comida;
    public $postres;
    public $preciofinal;
    public $fechaPrevista;
    public $fechaReal;
    public $activo; //con cliente esperando pedido” , ”con cliente comiendo”, “con cliente pagando” y “cerrada”.
    public $created_at;
    public $updated_at;
    public $foto;
       
    public function crearRegistro() // OJO HACERLO
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (nombre, alta, ultima_actualizacion) 
                                                              VALUES (:nombre)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }
}

?>