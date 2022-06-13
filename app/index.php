<?php
// Error Handling 
// Commit
error_reporting(-1);
ini_set('display_errors', 1);      

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

include_once './api/UsuarioAPI.php';
include_once './api/ProductoAPI.php';
include_once './api/MesaAPI.php';

include_once './db/AccesoDatos.php';

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('[/]', function (Request $request, Response $response) {    
  $response->getBody()->write("¡Comanda de Rus! :)");
  return $response;

});

//Usuarios
$app->post('/empleados/alta[/]', \UsuarioAPI::class . ':Alta'); 
//Productos 
$app->post('/productos/alta[/]', \ProductoAPI::class . ':Alta');  
//Mesas
$app->post('/mesa/alta[/]', \MesaAPI::class . ':Alta');  

$app->run();
