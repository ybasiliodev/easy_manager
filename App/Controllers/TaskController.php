<?php

namespace App\Controllers;

use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Services\Task\addTaskService;
use App\Services\Task\deleteTaskService;
use App\Services\Task\getAllTasksService;
use App\Services\Task\updateTaskStatusService;
use App\Utils\DateFormat;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

final class TaskController
{
    public function index(Response $response): Response
    {
        try {
            $taskRepository = new TaskRepository();
            $getAllTasksService = new getAllTasksService($taskRepository);
            $tasks = $getAllTasksService->exec();
            $response->getBody()->write(json_encode($tasks, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

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

            $manager = $request->getAttribute('logged_manager');

            $taskRepository = new TaskRepository();
            $userRepository = new UserRepository();
            $projectRepository = new ProjectRepository();
            $dateFormatUtil = new DateFormat();
            $addTaskService = new addTaskService($taskRepository, $projectRepository, $userRepository, $dateFormatUtil);

            $message = $addTaskService->exec($data, $manager);
            $response->getBody()->write(json_encode(["success" => true, "message" => $message], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode(["success" => true, "message" => $e->getMessage()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
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

            $taskRepository = new TaskRepository();
            $deleteTaskService = new deleteTaskService($taskRepository);

            $message = $deleteTaskService->exec($id,$manager);
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
            $manager = $request->getAttribute('logged_id');

            $taskRepository = new TaskRepository();
            $updateTaskStatus = new updateTaskStatusService($taskRepository);

            $message = $updateTaskStatus->exec($id,$manager,0);
            $response->getBody()->write(json_encode(['success' => true, "message" => $message], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(202);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode(['success' => false, "message" => $e->getMessage()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(403);
        }
    }
}