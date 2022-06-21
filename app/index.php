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
include_once './api/PedidoAPI.php';
include_once './api/PedidoProductoAPI.php';
include_once './db/AccesoDatos.php';
include_once './middlewares/UsuarioMW.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('[/]', function (Request $request, Response $response) {    
  $response->getBody()->write("¡Comanda de Rus! :)");
  return $response;

});

//Usuarios
$app->post('/empleados/login[/]', \UsuarioAPI::class . ':Login');  
$app->post('/empleados/alta[/]', \UsuarioAPI::class . ':Alta');
$app->get('/empleados/lista[/]', \UsuarioAPI::class . ':Listar');  


//Productos 
$app->post('/productos/alta[/]', \ProductoAPI::class . ':Alta');  
$app->get('/productos/lista[/]', \ProductoAPI::class . ':Listar');  


//Mesas
$app->post('/mesa/alta[/]', \MesaAPI::class . ':Alta'); 
$app->get('/mesa/lista[/]', \MesaAPI::class . ':Listar');  
$app->get('/mesa/export[/]', \MesaAPI::class . ':ExportarTabla');  
$app->post('/mesa/import[/]', \MesaAPI::class . ':ImportarTabla');  



//Pedido
$app->post('/pedido/nuevopedido[/]', \PedidoAPI::class . ':Alta')
->add(\UsuarioMW::class. ':ValidarMozo')
->add(\UsuarioMW::class. ':ValidarToken');
$app->post('/pedido/aniadirProducto[/]', \PedidoProductoAPI::class . ':Alta'); 
$app->post('/pedido/comiendo[/]', \PedidoAPI::class . ':PasarAComiendo'); 
$app->post('/pedido/pagando[/]', \PedidoAPI::class . ':PasarAPagando'); 
$app->post('/pedido/cerrar[/]', \PedidoAPI::class . ':CerrarPedido'); 
$app->post('/pedido/asignarfecha[/]', \PedidoProductoAPI::class . ':AsignarFechaPrevista'); 
$app->post('/pedido/enpreparacion[/]', \PedidoProductoAPI::class . ':PedidoEnPreparacion'); 
$app->post('/pedido/listo[/]', \PedidoProductoAPI::class . ':PedidoListo'); 



$app->get('/pedido/lista[/]', \PedidoAPI::class . ':Listar');  
$app->get('/pedido/listaBarra[/]', \PedidoProductoAPI::class . ':ListarPedidosBarra');  
$app->get('/pedido/listaChoperas[/]', \PedidoProductoAPI::class . ':ListarPedidosChoperas');  
$app->get('/pedido/listaCocina[/]', \PedidoProductoAPI::class . ':ListarPedidosCocina');  
$app->get('/pedido/listaCandybar[/]', \PedidoProductoAPI::class . ':ListarPedidosCandybar');  

$app->run();
