<?php

//require './Applications/XAMPP/xamppfiles/htdocs/slim-php-mysql-heroku/vendor/autoload.php';
require "../vendor/autoload.php";
use Firebase\JWT\JWT;

class Token
{
    private static $clave = 'P44r$Tpp';
    private static $encriptacion = ['HS256'];

    public static function GenerarToken($idUsuario, $tipo)
    {
        $ahora = new Datetime("now", new DateTimeZone('America/Buenos_Aires'));
        $exp = new Datetime("now", new DateTimeZone('America/Buenos_Aires'));
        $exp->modify('+60 days');
        $payload = array(
            'iat' => $ahora,
            'exp' => $exp,
            'idUsuario' => $idUsuario,
            'tipo' => $tipo,
        );
        return JWT::encode($payload, Token::$clave, Token::$encriptacion[0]);
    }

    public static function LeerToken($token){
        try
        {            
            $payload = JWT::decode($token, Token::$clave, Token::$encriptacion);
            $decoded = array("Estado" => "1", "Mensaje" => "Leído.", "Payload" => $payload);
        }
        catch(\Firebase\JWT\ExpiredException $e){
            $mensaje = $e->getMessage();
            $decoded = array("Estado" => "0", "Mensaje" => "$mensaje.");
        }
        catch(\Firebase\JWT\SignatureInvalidException $e){
            $mensaje = $e->getMessage();
            $decoded = array("Estado" => "0", "Mensaje" => "$mensaje");
        }
        catch(Exception $e){
            $mensaje = $e->getMessage();
            $decoded = array("Estado" => "0", "Mensaje" => "$mensaje");
        }        
        return $decoded;
    }

}


?>