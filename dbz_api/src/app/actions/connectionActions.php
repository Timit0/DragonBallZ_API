<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    
    $app->get('/test', function (Request $request, Response $response, array $args) 
    {
        $response->getBody()->write("test");

        return $response;
    });

    $app->post('/addUser', function (Request $request, Response $response) {
        $params = $request->getParsedBody();
        $username = $params['username'];
        $password = $params['password'];
        
        $user = new User(username: $username, password: $password);
        $is_added = $user->add_in_db();

        $data = [];
        if($is_added) {
            $data = [
                'success' => true,
                'message' => "User added successfully $user->username",
            ];
            $response = $response->withHeader('Content-Type', 'application/json')
                                 ->withStatus(200);
        } else {
            $data = [
                'success' => false,
                'message' => "$user->username already existing",
            ];
            $response = $response->withHeader('Content-Type', 'application/json')
                                 ->withStatus(200);
        }
        
        $response->getBody()->write(json_encode($data));
        return $response;
    });

    $app->post('/logIn', function (Request $request, Response $response) {
        $params = $request->getParsedBody();
        $username = $params['username'];
        $password = $params['password'];
        
        $user = new User(username: $username, password: $password);
        $can_log = $user->can_log();
        
        $data = [];
        if($can_log) {
            $data = [
                'success' => true,
                'message' => "Logged successfully",
            ];
            $response = $response->withHeader('Content-Type', 'application/json')
                                 ->withStatus(200);
        } else {
            $data = [
                'success' => false,
                'message' => "Username or password is wrong",
            ];
            $response = $response->withHeader('Content-Type', 'application/json')
                                 ->withStatus(200);
        }

        $response->getBody()->write(json_encode($data));
        return $response;
    });
};
