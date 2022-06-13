<?php
include_once("Entidades/Usuario.php");

class UsuarioAPI
{
    public function Alta($request, $response, $args)
    {
        $params = $request->getParsedBody();
        //var_dump($params);

        $usuario = new Usuario();
        $usuario->dni = $params["dni"];
        $usuario->clave = $params["clave"];
        $usuario->tipo = $params["tipo"];
        $alta = Usuario::Alta($usuario);
        switch($alta)
        {
            case -1:
                $respuesta = "Problema generando el alta;";
                break;
            case 0:
                $respuesta = "ERROR. No existe este tipo.";
                break;
            case 1:
                $respuesta = "El usuario ya existía en la BD. Se ha pasado activo si no lo estaba y se ha actualizado la información.";
                break;
            case 2:
                $respuesta = "Usuario creado con éxito.";
                break;
            default:
                $respuesta = "Nunca llega al alta";
        }

        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }
}

?>