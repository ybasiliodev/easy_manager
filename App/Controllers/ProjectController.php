<?php

namespace App\Controllers;

use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Services\Project\addProjectService;
use App\Services\Project\deleteProjectService;
use App\Services\Project\getAllProjectsService;
use App\Services\Project\updateProjectStatusService;
use App\Utils\DateFormat;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

final class ProjectController
{
    public function index(Response $response): Response
    {
        try {
            $projectRepository = new ProjectRepository();
            $getAllProjectsService = new getAllProjectsService($projectRepository);
            $projects = $getAllProjectsService->exec();
            $response->getBody()->write(json_encode($projects, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode(["success" => false, "message" => $e->getMessage()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }
    }

    public function post(Request $request, Response $response): Response
    {
        try {
            $routeContext = RouteContext::fromRequest($request);
            $route = $routeContext->getRoute();
            $data = $request->getParsedBody();
            $id = $route->getArgument('id');
            $data['id'] = $id ?: null;
            $statusCode = $id ? 204 : 201;

            $userId = $request->getAttribute('logged_id');
            $manager = $request->getAttribute('logged_manager');

            $userRepository = new UserRepository();
            $projectRepository = new ProjectRepository();
            $dateFormatUtil = new DateFormat();
            $addProjectService = new addProjectService($projectRepository, $userRepository, $dateFormatUtil);

            $message = $addProjectService->exec($data,$userId,$manager);
            $response->getBody()->write(json_encode(["success" => true, "message" => $message], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode(["success" => false, "message" => $e->getMessage()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }
    }

    public function destroy(Request $request, Response $response): Response
    {
        try {
            $routeContext = RouteContext::fromRequest($request);
            $route = $routeContext->getRoute();
            $id = $route->getArgument('id');
            $manager = $request->getAttribute('logged_manager');

            $projectRepository = new ProjectRepository();
            $deleteProjectService = new deleteProjectService($projectRepository);

            $message = $deleteProjectService->exec($id,$manager);
            $response->getBody()->write(json_encode(['success' => true, "message" => $message], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(202);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode(['success' => false, "message" => $e->getMessage()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }
    }

    public function patch(Request $request, Response $response): Response
    {
        try {
            $routeContext = RouteContext::fromRequest($request);
            $route = $routeContext->getRoute();
            $id = $route->getArgument('id');
            $manager = $request->getAttribute('logged_manager');

            $projectRepository = new ProjectRepository();
            $taskRepository = new TaskRepository();
            $updateProjectStatusService = new updateProjectStatusService($projectRepository, $taskRepository);

            $message = $updateProjectStatusService->exec($id,$manager);
            $response->getBody()->write(json_encode(['success' => true, "message" => $message], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(202);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode(['success' => false, "message" => $e->getMessage()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }
    }
}