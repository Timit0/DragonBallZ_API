<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) 
{
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->get('/phpinfo', function (Request $request, Response $response, array $args) 
{
    $response->getBody()->write(phpinfo());

    return $response;
});

$app->get('/addUserGet', function (Request $request, Response $response, array $args) 
{
    $params = $request->getQueryParams();
    $username = $params['username'];
    $password = $params['password'];
    
    $user = new User($username, $password);
    $user->add_in_db();
    $response->getBody()->write("Hello user, $user->username with $user->password");
    
    return $response;
});

$app->post('/addUser', function (Request $request, Response $response, array $args)
{
    $params = $request->getParsedBody();
    $username = $params['username'];
    $password = $params['password'];
    
    $user = new User($username, $password);
    $is_added = $user->add_in_db();

    return $response;
});

$app->run();
