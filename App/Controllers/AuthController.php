<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class AuthController
{
    public function index(Request $request, Response $response)
    {
        try {
            $key = getenv('JWT_SECRET_KEY');
            $data = $request->getParsedBody();

            $userRep = new UserRepository();
            $user = $userRep->getUserByEmailAndDocument($data['email'], $data['cpf']);

            if ($user) {
                $token = array(
                    "id" => $user['id'],
                    "email" => $user['email'],
                    "cpf" => $user['cpf'],
                    "manager" => $user['manager']
                );
                $jwt = JWT::encode($token, $key, 'HS256');

                $response->getBody()->write(json_encode(["success" => true, "token" => $jwt], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
            }

            $response->getBody()->write(json_encode(["success" => false, "message" => "user not found"], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode(["success" => false, "message" => "error searching for user"], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }
}