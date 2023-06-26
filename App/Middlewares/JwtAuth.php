<?php

namespace App\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response as NewResponse;

class JwtAuth
{
    /**
     * @throws \Exception
     */
    public function __invoke(Request $request, Handler $handler): Response
    {
        $jwtHeader = $request->getHeaderLine('Authorization');
        $exceptionResponse = new NewResponse();

        if (!$jwtHeader) {
            $exceptionResponse->getBody()->write(json_encode(['success' => false, "message" => "JWT Token required."],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $exceptionResponse->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        $jwt = explode('Bearer ', $jwtHeader);

        if (!isset($jwt[1])) {
            $exceptionResponse->getBody()->write(json_encode(['success' => false, "message" => "JWT Token invalid."],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $exceptionResponse->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        try {
            $decoded = $this->checkToken($jwt[1]);
            $request = $request->withAttribute('logged_manager', $decoded->manager);
            $request = $request->withAttribute('logged_id', $decoded->id);
            return $handler->handle($request);
        } catch (\Exception $e) {
            $exceptionResponse->getBody()->write(json_encode(['success' => false, "message" => $e->getMessage()],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $exceptionResponse->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }

    /**
     * @throws \Exception
     */
    private function checkToken($token): \stdClass
    {
        try {
            return JWT::decode($token, new Key(getenv('JWT_SECRET_KEY'), 'HS256'));
        } catch (\UnexpectedValueException) {
            throw new \Exception('Forbidden: you are not authorized.');
        }
    }

}