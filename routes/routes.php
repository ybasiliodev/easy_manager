<?php

use App\Controllers\AuthController;
use App\Controllers\ProjectController;
use App\Controllers\TaskController;
use App\Controllers\UserController;
use App\Middlewares\JwtAuth;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    $app->group('/api/v1', function (RouteCollectorProxy $group) {
        $group->get('/user', [UserController::class, 'index']);
        $group->post('/user', [UserController::class, 'post']);

        $group->get('/project', [ProjectController::class, 'index']);
        $group->post('/project', [ProjectController::class, 'post']);
        $group->put('/project/{id}', [ProjectController::class, 'post']);
        $group->delete('/project/{id}', [ProjectController::class, 'destroy']);
        $group->patch('/project/{id}', [ProjectController::class, 'patch']);

        $group->get('/task', [TaskController::class, 'index']);
        $group->post('/task', [TaskController::class, 'post']);
        $group->put('/task/{id}', [TaskController::class, 'post']);
        $group->delete('/task/{id}', [TaskController::class, 'destroy']);
        $group->patch('/task/{id}', [TaskController::class, 'patch']);
    })->add(new JwtAuth());

    $app->post('/auth', [AuthController::class, 'index']);
};