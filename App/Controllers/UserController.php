<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\User\AddUserService;
use App\Services\User\GetAllUsersService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class UserController
{
    public function index(Request $request, Response $response)
    {
        try {
            $userRepository = new UserRepository();
            $getAllUsersService = new GetAllUsersService($userRepository);
            $users = $getAllUsersService->exec();
            $response->getBody()->write(json_encode($users, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (\Throwable $e) {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(403);
        }
    }

    public function post(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();
            $inputDto = [
                "username" => $data["username"],
                "cpf" => $data["cpf"],
                "email" => $data["email"],
                "manager" => $data["manager"]
            ];

            $userRepository = new UserRepository();
            $addUserService = new AddUserService($userRepository);
            $message = $addUserService->exec($inputDto);
            $response->getBody()->write(json_encode($message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode($e->getMessage(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(403);
        }
    }
}