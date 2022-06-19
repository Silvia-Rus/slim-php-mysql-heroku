<?php
include_once("Entidades/Usuario.php");

class UsuarioAPI
{
    public function Alta($request, $response, $args)
    {
        try
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
            $lista = AccesoDatos::ImprimirTabla('usuario', 'Usuario');
            $payload = json_encode(array("listaUsuarios" => $lista));
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