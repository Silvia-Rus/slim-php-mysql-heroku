<?php
include_once("Entidades/Reportes.php");

class ReportesAPI
{

    public function DemoraPedidosCerrados($request, $response, $args)
    {
        try
        {
            $lista = Reportes::DemoraPedidosCerrados();
            $payload = json_encode(array("listaPedidosCerrados" => $lista));
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


    public function EstadoMesas($request, $response, $args)
    {
        try
        {
            $lista = Reportes::EstadoMesas();
            $payload = json_encode(array("estadoMesas" => $lista));
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


    public function MejoresComentarios($request, $response, $args)
    {
        try
        {
            $lista = Reportes::MejoresComentarios();
            $payload = json_encode(array("mejoresComentarios" => $lista));
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

    public function MesaMasUsada($request, $response, $args)
    {
        try
        {
            $lista = Reportes::MesaMasUsada();
            $payload = json_encode(array("mesaMasUsada" => $lista));
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