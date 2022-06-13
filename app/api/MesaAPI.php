<?php
include_once("Entidades/Mesa.php");

class MesaAPI
{
    public function Alta($request, $response, $args)
    {
        $params = $request->getParsedBody();
        //var_dump($params);
        $mesa = new Mesa();
        $mesa->nombre= $params["nombre"];
        $respuesta = Mesa::Alta($mesa);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }
}

?>