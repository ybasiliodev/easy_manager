<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\User\AddUserService;
use App\Services\User\GetAllUsersService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class UserController
{
    public function index(Response $response): Response
    {
        try {
            $userRepository = new UserRepository();
            $getAllUsersService = new GetAllUsersService($userRepository);
            $users = $getAllUsersService->exec();
            $response->getBody()->write(json_encode($users, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode(["success" => false, "message" => $e->getMessage()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }
    }

    public function post(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            $userRepository = new UserRepository();
            $addUserService = new AddUserService($userRepository);
            $message = $addUserService->exec($data);
            $response->getBody()->write(json_encode(['success' => true, 'message' => $message], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode(['success' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }
    }
}