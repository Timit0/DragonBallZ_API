<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    
    $app->get('/test_host', function (Request $request, Response $response, array $args) 
    {
        $params = $request->getQueryParams();
        $host_username = $params['username'];
        $result = HostServer::can_add_guest_user($host_username);
        // $result = HostServer::test($host_username)['is_user_guest_null'];
        
        $data = [];
        if($result === true)
        {
            $data = [
                'success' => true,
                'message' => "Can connect",
            ];
        }else
        {
            $data = [
                'success' => false,
                'message' => "Can't connect",
            ];
        }
        
        $response->getBody()->write(json_encode($data));
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

    $app->post('/get_all_available_host_server', function (Request $request, Response $response) {
        $result = HostServer::get_all_available_host_server();
        $jsonResult = json_encode($result);
        $data = [
            'success' => true,
            'message' => "Host server deleted successfully",
            'host_server_list' => $jsonResult,
        ];
        $response->getBody()->write(json_encode($data));
        return $response;
    });

    $app->post('/get_all_host_server', function (Request $request, Response $response) {
        $result = HostServer::get_all_host_server();
        $jsonResult = json_encode($result);
        $data = [
            'success' => true,
            'message' => "Host server deleted successfully",
            'host_server_list' => $jsonResult,
        ];
        $response->getBody()->write(json_encode($data));
        return $response;
    });

    $app->post('/add_player_guest_to_host_server', function (Request $request, Response $response) {
        $params = $request->getParsedBody();
        $host_username = $params['host_username'];
        $guest_username = $params['guest_username'];

        $result = HostServer::add_guest_user($guest_username, $host_username);
        $data = [
            'success' => true,
            'message' => "Connected to serv",
        ];
        $response->getBody()->write(json_encode($data));
        return $response;
    });

    $app->post('/can_add_guest_to_host_server', function (Request $request, Response $response) {
        $params = $request->getParsedBody();
        $host_username = $params['username'];
        //$result = HostServer::can_add_guest_user($host_username);
        $result = HostServer::can_add_guest_user($host_username);
        
        $data = [];
        if($result === true)
        {
            $data = [
                'success' => true,
                'message' => "Can connect FDP $host_username",
            ];
        }else
        {
            $data = [
                'success' => false,
                'message' => "Can't connect",
            ];
        }
        
        $response->getBody()->write(json_encode($data));
        return $response;
    });
};
