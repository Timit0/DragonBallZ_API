<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    
    $app->get('/test_host', function (Request $request, Response $response, array $args) 
    {
        $params = $request->getQueryParams();
        $host_username = $params['host_username'];

        $host_user = new User(username: $host_username);
        $message = $host_user->username;
        echo "<script type='text/javascript'>alert('$message');</script>";
        $user = User::get_user_by_name($host_username);

        $response->getBody()->write($user->id);

        return $response;
    });

    $app->post('/create_host_server', function (Request $request, Response $response) {
        $params = $request->getParsedBody();
        $host_username = $params['host_username'];
        $host_ip = $params['host_ip'];

        $is_added = HostServer::add_host_server($host_username, $host_ip);

        $data = [];
        if($is_added) {
            $data = [
                'success' => true,
                'message' => "Host server added successfully",
            ];
            $response = $response->withHeader('Content-Type', 'application/json')
                                 ->withStatus(200);
        } else {
            $data = [
                'success' => false,
                'message' => "Probleme",
            ];
            $response = $response->withHeader('Content-Type', 'application/json')
                                 ->withStatus(200);
        }
        
        $response->getBody()->write(json_encode($data));
        return $response;
    });

    $app->post('/delete_host_server', function (Request $request, Response $response) {
        $params = $request->getParsedBody();
        $username = $params['username'];

        $is_added = HostServer::delete_host_server($username);

        $data = [];
        if($is_added) {
            $data = [
                'success' => true,
                'message' => "Host server deleted successfully",
            ];
            $response = $response->withHeader('Content-Type', 'application/json')
                                 ->withStatus(200);
        } else {
            $data = [
                'success' => false,
                'message' => "Probleme",
            ];
            $response = $response->withHeader('Content-Type', 'application/json')
                                 ->withStatus(200);
        }
        
        $response->getBody()->write(json_encode($data));
        return $response;
    });
};
