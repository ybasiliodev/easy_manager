<?php

namespace App\Services\Task;

use App\Interfaces\TaskRepositoryInterface;

class deleteTaskService
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
    public function exec(int $id, bool $manager) {
        $this->validateData($id, $manager);
        return $this->taskRepositoryInterface->deleteTask($id);
    }

    /**
     * @throws \Exception
     */
    private function validateData(int $id, bool $manager) {
        if (empty($id) || $id == 0) {
            throw new \Exception("Enter a valid task id.");
        }

        if (!$manager) {
            throw new \Exception("The task can only be deleted by a manager user.");
        }
    }
}