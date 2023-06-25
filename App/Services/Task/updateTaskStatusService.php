<?php

namespace App\Services\Task;

use App\Interfaces\TaskRepositoryInterface;

class updateTaskStatusService
{
    /** @var TaskRepositoryInterface */
    private TaskRepositoryInterface $taskRepositoryInterface;

    public function __construct(TaskRepositoryInterface $taskRepositoryInterface)
    {
        $this->taskRepositoryInterface = $taskRepositoryInterface;
    }

    /**
     * @throws \Exception
     */
    public function exec(int $id, int $userId, int $status) {
        $this->validateData($id, $userId);
        $status = $status ?: 0;
        return $this->taskRepositoryInterface->updateTaskStatus($id, $status);
    }

    /**
     * @throws \Exception
     */
    private function validateData(int $id, int $userId) {
        if (empty($id) || $id == 0) {
            throw new \Exception("Enter a valid task id.");
        }

        $taskData = $this->taskRepositoryInterface->getTaskById($id);

        if (!$taskData) {
            throw new \Exception("Task not found.");
        }

        if ($taskData['user_id'] != $userId) {
            throw new \Exception("The task can only be closed by his executor user.");
        }

    }
}