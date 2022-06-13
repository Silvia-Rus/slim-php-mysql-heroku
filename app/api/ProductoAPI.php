<?php
include_once("Entidades/Producto.php");

class ProductoAPI
{
    public function Alta($request, $response, $args)
    {
        $params = $request->getParsedBody();
        //var_dump($params);

        $producto = new Producto();
        $producto->id_sector = $params["sector"];
        $producto->nombre= $params["nombre"];
        $alta = Producto::Alta($producto);
        switch($alta)
        {
            case -1:
                $respuesta = "Problema generando el alta.";
                break;
            case 0:
                $respuesta = "ERROR. No existe este sector.";
                break;
            case 1:
                $respuesta = "El producto ya existía en la BD. Se ha pasado activo si no lo estaba y se ha actualizado la información.";
                break;
            case 2:
                $respuesta = "Producto creado con éxito.";
                break;
            default:
                $respuesta = "Nunca llega al alta";
        }

        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }
}

?>