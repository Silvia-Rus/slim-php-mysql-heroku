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


    public function ListarPedidosBarra($request, $response, $args)
    {
        try
        {
            $lista = AccesoDatos::ObtenerPedidosPorSector('1');
            $payload = json_encode(array("PedidosActivosBarra" => $lista));
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

    public function ListarPedidosChoperas($request, $response, $args)
    {
        try
        {
            $lista = AccesoDatos::ObtenerPedidosPorSector('2');
            var_dump($lista);
            $payload = json_encode(array("PedidosActivosChoperas" => $lista));
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

    public function ListarPedidosCocina($request, $response, $args)
    {
        try
        {
            $lista = AccesoDatos::ObtenerPedidosPorSector('3');
            $payload = json_encode(array("PedidosActivosCocina" => $lista));
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

    public function ListarPedidosCandybar($request, $response, $args)
    {
        try
        {
            $lista = AccesoDatos::ObtenerPedidosPorSector('4');
            $payload = json_encode(array("PedidosActivosCandybar" => $lista));
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

    public function AsignarFechaPrevista($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            $idPedidoProducto = $params["idPedido"];
            $tiempo = $params["tiempo"];
            PedidoProducto::AsignarFechaPrevista($idPedidoProducto, $tiempo);
            $payload = json_encode("Fecha prevista asignada con éxito");
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

    public function PedidoEnPreparacion($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            $idPedidoProducto = $params["id"];
            PedidoProducto::CambiarEstado($idPedidoProducto, '1');
            $payload = json_encode("Pedido en preparación.");
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

    public function PedidoListo($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            $idPedidoProducto = $params["id"];
            PedidoProducto::CambiarEstado($idPedidoProducto, '2');
            $payload = json_encode("Pedido listo para servir.");
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

