<?php
include_once("Entidades/Mesa.php");
include_once("importexport/CSV.php");


class MesaAPI
{
    public function Alta($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            //var_dump($params);
            $mesa = new Mesa();
            $mesa->nombre= $params["nombre"];
            $respuesta = Mesa::Alta($mesa);
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
            $lista = AccesoDatos::ImprimirTabla('mesa', 'Mesa');
            $payload = json_encode(array("listaMesas" => $lista));
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

    public function ExportarTabla($request, $response, $args)
    {
        try
        {
            CSV::ExportarTabla('mesa', 'Mesa', 'mesa.csv');
            $payload = json_encode("Tabla exportada con éxito");
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

    public function ImportarTabla($request, $response, $args)
    {
        try
        {
            $archivo = ($_FILES["archivo"]);
            //var_dump($archivo);
            Mesa::CargarCSV($archivo["tmp_name"]);
            $payload = json_encode("Carga exitosa.");
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