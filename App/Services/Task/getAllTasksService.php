<?php

namespace App\Services\Task;

use App\Interfaces\TaskRepositoryInterface;

class getAllTasksService
{
    /** @var TaskRepositoryInterface */
    private TaskRepositoryInterface $taskRepositoryInterface;

    public function __construct(TaskRepositoryInterface $taskRepositoryInterface)
    {
        $this->taskRepositoryInterface = $taskRepositoryInterface;
    }

    public function exec()
    {
        return $this->taskRepositoryInterface->getAllTasks();
    }
}