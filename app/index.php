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
include_once './api/EncuestaAPI.php';
include_once './api/SectorAPI.php';
include_once './api/TipoUsuarioAPI.php';



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

//Sector
$app->post('/sector/alta[/]', \SectorAPI::class . ':Alta');
$app->post('/sector/modificacion[/]', \SectorAPI::class . ':Modificacion');
$app->delete('/sector/baja/{id}[/]', \SectorAPI::class . ':Baja');
$app->get('/sector/lista[/]', \SectorAPI::class . ':Listar');  

//TipoUsuario
$app->post('/tipousuario/alta[/]', \TipoUsuarioAPI::class . ':Alta');
$app->post('/tipousuario/modificacion[/]', \TipoUsuarioAPI::class . ':Modificacion');
$app->delete('/tipousuario/baja/{id}[/]', \TipoUsuarioAPI::class . ':Baja');
$app->get('/tipousuario/lista[/]', \TipoUsuarioAPI::class . ':Listar'); 

//Mesas
$app->post('/mesa/alta[/]', \MesaAPI::class . ':Alta'); 
$app->delete('/mesa/baja/{id}[/]', \MesaAPI::class . ':Baja');
$app->post('/mesa/modificacion[/]', \MesaAPI::class . ':Modificacion'); 
$app->get('/mesa/lista[/]', \MesaAPI::class . ':Listar'); 

$app->get('/mesa/export[/]', \MesaAPI::class . ':ExportarTabla');  
$app->post('/mesa/import[/]', \MesaAPI::class . ':ImportarTabla');  


//Usuarios
$app->post('/empleados/alta[/]', \UsuarioAPI::class . ':Alta');
$app->delete('/empleados/baja/{id}[/]', \UsuarioAPI::class . ':Baja');
$app->post('/empleados/modificacion[/]', \UsuarioAPI::class . ':Modificacion'); 
$app->get('/empleados/lista[/]', \UsuarioAPI::class . ':Listar');  

$app->post('/empleados/login[/]', \UsuarioAPI::class . ':Login');  


//Productos 
$app->post('/productos/alta[/]', \ProductoAPI::class . ':Alta'); 
$app->delete('/productos/baja/{id}[/]', \ProductoAPI::class . ':Baja');
$app->post('/productos/modificacion[/]', \ProductoAPI::class . ':Modificacion');  
$app->get('/productos/lista[/]', \ProductoAPI::class . ':Listar');  


//Pedido
$app->post('/pedido/alta[/]', \PedidoAPI::class . ':Alta')
->add(\UsuarioMW::class. ':ValidarMozo')
->add(\UsuarioMW::class. ':ValidarToken');
$app->delete('/pedido/baja/{id}[/]', \PedidoAPI::class . ':Baja');

$app->post('/pedido/nuevopedido[/]', \PedidoAPI::class . ':Alta');




$app->post('/pedido/aniadirProducto[/]', \PedidoProductoAPI::class . ':Alta'); 
$app->post('/pedido/comiendo[/]', \PedidoAPI::class . ':PasarAComiendo'); 
$app->post('/pedido/pagando[/]', \PedidoAPI::class . ':PasarAPagando'); 
$app->post('/pedido/cerrar[/]', \PedidoAPI::class . ':CerrarPedido'); 
$app->post('/pedido/asignarfecha[/]', \PedidoProductoAPI::class . ':AsignarFechaPrevista'); 
$app->post('/pedido/enpreparacion[/]', \PedidoProductoAPI::class . ':PedidoEnPreparacion'); 
$app->post('/pedido/listo[/]', \PedidoProductoAPI::class . ':PedidoListo'); 

$app->post('/encuesta/nuevaEncuesta[/]', \EncuestaAPI::class . ':Alta'); 


$app->get('/pedido/lista[/]', \PedidoAPI::class . ':Listar');  
$app->get('/pedido/listaBarra[/]', \PedidoProductoAPI::class . ':ListarPedidosBarra');  
$app->get('/pedido/listaChoperas[/]', \PedidoProductoAPI::class . ':ListarPedidosChoperas');  
$app->get('/pedido/listaCocina[/]', \PedidoProductoAPI::class . ':ListarPedidosCocina');  
$app->get('/pedido/listaCandybar[/]', \PedidoProductoAPI::class . ':ListarPedidosCandybar');  

$app->run();
