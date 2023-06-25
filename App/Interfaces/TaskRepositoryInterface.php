<?php

namespace App\Interfaces;

use App\Models\Task;

interface TaskRepositoryInterface
{
    public function getAllTasks();
    public function getTaskById(int $id);
    public function getClosedTasksStatusByProjectId(int $projectId);
    public function addTask(Task $task);
    public function updateTask(Task $task);
    public function updateTaskStatus(int $id, int $status);
    public function deleteTask(int $id);
    public function endTask(int $id);
}