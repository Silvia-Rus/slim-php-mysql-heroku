<?php

include_once("token/Token.php");


class UsuarioMW
{

    public static function ValidarToken($request, $response, $next)
    {

        $token = $request->getHeader("token");
        $contenidoToken = Token::LeerToken($token[0]);
        if($contenidoToken["Estado"] == "1"){
            $request = $request->withAttribute("payload", $contenidoToken["Mensaje"]);
            return $next($request,$response);
        }
        else
        {
            $newResponse = $response->withJson($contenidoToken,200);
            return $newResponse;
        }
    }

    public static function ValidarSocio($request,$response,$next){
        $payload = $request->getAttribute("payload")["Payload"];

        if($payload->tipo == "4")
        {
            return $next($request,$response);
        }
        else{
            $respuesta = array("Estado" => "0", "Mensaje" => "No tienes acceso a esta funcionalidad (solo socios).");
            $newResponse = $response->withJson($respuesta["Mensaje"],200);
            return $newResponse;
        }
    }

    public static function ValidarMozo($request,$response,$next)
    {
        $payload = $request->getAttribute("payload")["Payload"];
        $tipoUsuario = $payload->tipo;
        if($tipoUsuario == "4" || $tipoUsuario == "5")
        {
            return $next($request,$response);
        }
        else
        {
            $respuesta = array("Estado" => "0", "Mensaje" => "No tienes acceso a esta funcionalidad (solo mozos).");
            $newResponse = $response->withJson($respuesta["Mensaje"],200);
            return $newResponse;
        }
    }
}
?>


