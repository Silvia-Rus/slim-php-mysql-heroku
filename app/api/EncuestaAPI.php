<?php
include_once("Entidades/Encuesta.php");

class EncuestaAPI
{
    public function Alta($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            //var_dump($params);
            $encuesta = new Encuesta();
            $encuesta->cliente= $params["cliente"];
            $encuesta->nota_restaurante= $params["nota_restaurante"];
            $encuesta->nota_mozo = $params["nota_mozo"];
            $encuesta->nota_cocinero = $params["nota_cocinero"];
            $encuesta->texto = $params["texto"];
            $respuesta = Encuesta::Alta($encuesta);
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