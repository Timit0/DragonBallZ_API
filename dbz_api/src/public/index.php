<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/phpinfo', function (Request $request, Response $response, array $args) 
{
    $response->getBody()->write(phpinfo());

    return $response;
});

(require __DIR__ . '/../app/actions/connectionActions.php')($app);
(require __DIR__ . '/../app/actions/hostServerActions.php')($app);

$app->run();
