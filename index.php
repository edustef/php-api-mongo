<?php
ini_set('display_errors', 'on');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Allow-Access-Origin: application/json');

require_once __DIR__ . '/vendor/autoload.php';

use api\controllers\ClienteController;
use api\controllers\LibroController;
use api\controllers\PrestamoController;
use edustef\mvcFrame\Application;
use edustef\mvcFrame\exceptions\ForbiddenException;

if (file_exists(__DIR__ . '/.env')) {
  $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();
}

$config = [
  'db' => [
    'dsn' => $_ENV['DB_DSN'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD']
  ],
];

$app = new Application($config);

$app->router->get('/', function ($request, $response) {
  throw new ForbiddenException();
});
$app->router->get('/clientes', [ClienteController::class, 'getClientes']);
$app->router->post('/clientes', [ClienteController::class, 'postCliente']);
$app->router->put('/clientes', [ClienteController::class, 'editCliente']);
$app->router->delete('/clientes', [ClienteController::class, 'deleteCliente']);

$app->router->get('/libros', [LibroController::class, 'getLibros']);
$app->router->post('/libros', [LibroController::class, 'postLibro']);
$app->router->put('/libros', [LibroController::class, 'editLibro']);
$app->router->delete('/libros', [LibroController::class, 'deleteLibro']);


$app->router->get('/prestamos', [PrestamoController::class, 'getPrestamos']);
$app->router->post('/prestamos', [PrestamoController::class, 'postPrestamo']);
$app->router->put('/prestamos', [PrestamoController::class, 'editPrestamo']);
$app->router->delete('/prestamos', [PrestamoController::class, 'deletePrestamo']);

$app->run();
