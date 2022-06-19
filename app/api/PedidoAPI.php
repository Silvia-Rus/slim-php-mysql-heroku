<?php

include_once("Entidades/Cliente.php");
include_once("Entidades/Pedido.php");

class PedidoAPI
{
    public function Alta($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            //var_dump($params);
            $cliente = new Cliente($params["cliente"]);
            $pedido = new Pedido();
            $pedido->id_mesa= $params["mesa"];
            $pedido->id_cliente =  Cliente::Alta($cliente);
            $pedido->fecha_prevista = $params["estara_en"];
            $alta = Pedido::Alta($pedido);
            switch($alta)
            {
                case '1':
                    $respuesta = 'Pedido generado.';
                    break;
                case '0':
                    $respuesta = 'No se generó el pedido pues la mesa está ocupada';
                    break;    
            }
            $payload = json_encode($respuesta);
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');
        }
        catch(Throwable $mensaje)
        {
            printf("Error al dar de alta: <br> $mensaje .<br>");
        }
        finally
        {
            return $newResponse;
        }
    }


    public function Listar($request, $response, $args)
    {
        try
        {
            $lista = AccesoDatos::ImprimirTabla('pedido', 'Pedido');
            $payload = json_encode(array("listaPedidos" => $lista));
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');
        }
        catch(Throwable $mensaje)
        {
            printf("Error al listar: <br> $mensaje .<br>");
        }
        finally
        {
            return $newResponse;
        }    
    }


}



?>