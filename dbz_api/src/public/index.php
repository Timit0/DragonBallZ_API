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
    $response->getBody()->write("Hello, $name, ".phpinfo()."");

    $user = new User("zoro", "nan");

    return $response;
});

$app->get('/addUser', function (Request $request, Response $response, array $args) 
{
    $params = $request->getQueryParams();
    $username = $params['username'];
    $password = $params['password'];
    
    $user = new User($username, $password);
    $user->add_in_db();
    $response->getBody()->write("Hello user, $user->username with $user->password");
    
    return $response;
});

$app->run();
