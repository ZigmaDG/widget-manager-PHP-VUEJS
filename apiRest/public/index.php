<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;
$app->add(new Tuupola\Middleware\CorsMiddleware);
// Ruta clientes
require '../src/routes/widgets.php';

$app->run();