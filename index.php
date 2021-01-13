<?php
ini_set('display_errors', 'on');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Allow-Access-Origin: application/json');

require_once __DIR__ . '/vendor/autoload.php';

use api\controllers\ClienteController;
use api\controllers\LibroController;
use api\controllers\PrestamoController;
use api\controllers\TestController;
use edustef\mvcFrame\Application;
use edustef\mvcFrame\exceptions\ForbiddenException;

if (file_exists(__DIR__ . '/.env')) {
  $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();
}

$config = [
  'db' => [
    'dbname' => $_ENV['DB_NAME'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'db_local' => $_ENV['DB_LOCAL'] === 'true' ? true : false
  ],
];

$app = new Application($config);

$app->router->get('/tests', [TestController::class, 'resolve']);

$app->run();
