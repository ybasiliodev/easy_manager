<?php

namespace App\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class JwtAuth
{
    /**
     * @throws \Exception
     */
    public function __invoke(Request $request, Handler $handler): Response
    {
        $jwtHeader = $request->getHeaderLine('Authorization');

        if (!$jwtHeader) {
            throw new \Exception("JWT Token required.");
        }

        $jwt = explode('Bearer ', $jwtHeader);

        if (!isset($jwt[1])) {
            throw new \Exception("JWT Token invalid.");
        }

        try {
            $decoded = $this->checkToken($jwt[1]);
            $request = $request->withAttribute('logged_manager', $decoded->manager);
            $request = $request->withAttribute('logged_id', $decoded->id);
            return $handler->handle($request);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    private function checkToken($token)
    {
        try {
            return JWT::decode($token, new Key(getenv('JWT_SECRET_KEY'), 'HS256'));
        } catch (\UnexpectedValueException) {
            throw new \Exception('Forbidden: you are not authorized.', 403);
        }
    }

}