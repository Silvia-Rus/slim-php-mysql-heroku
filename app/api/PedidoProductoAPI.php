<?php

include_once("Entidades/PedidoProducto.php");


class PedidoProductoAPI
{   
    public function Alta($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            //var_dump($params);
            $pedidoProducto = new PedidoProducto();
            $pedidoProducto->id_pedido = $params["mesa"];
            $pedidoProducto->id_producto = $params["producto"];
            $pedidoProducto->cantidad = $params["cantidad"];
            $alta = PedidoProducto::Alta($pedidoProducto);
    
            switch($alta)
            {
                case 0:
                    $respuesta = "No existe la mesa o no está ocupada. Pruebe a grabarla o a abrir pedido.";
                    break;
                case 1:
                    $respuesta = "Pedido grabado con éxito :).";
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
}


?>

