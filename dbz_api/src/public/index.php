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

$app->post('/addUser', function (Request $request, Response $response, array $args)
{
    $params = $request->getParsedBody();
    $username = $params['username'];
    $password = $params['password'];
    
    $user = new User($username, $password);
    $is_added = $user->add_in_db();

    $json = "";
    $data = [];
    if($is_added)
    {
        $data = [
            'success' => true,
            'message' => "User added successfully $user->username",
        ];

        $json = json_encode($data);

        $response = $response->withHeader('Content-Type', 'application/json')
                             ->withStatus(200);

        $response->getBody()->write($json);
    }else
    {
        $data = [
            'success' => false,
            'message' => "$user->username already existing",
        ];

        $json = json_encode($data);

        $response = $response->withHeader('Content-Type', 'application/json')
                             ->withStatus(200);

        $response->getBody()->write($json);
    }

    return $response;
});

$app->post('/logIn', function (Request $request, Response $response, array $args) 
{
    $params = $request->getParsedBody();
    $username = $params['username'];
    $password = $params['password'];
    
    $user = new User($username, $password);
    $can_log = $user->can_log();
    
    $json = "";
    $data = [];
    if($can_log)
    {
        $data = [
            'success' => true,
            'message' => "Logged successfully",
        ];

        $json = json_encode($data);

        $response = $response->withHeader('Content-Type', 'application/json')
                             ->withStatus(200);

        $response->getBody()->write($json);
    }else
    {
        $data = [
            'success' => false,
            'message' => "Username or password is wrong",
        ];

        $json = json_encode($data);

        $response = $response->withHeader('Content-Type', 'application/json')
                             ->withStatus(200);

        $response->getBody()->write($json);
    }
    
    return $response;
});

$app->run();
