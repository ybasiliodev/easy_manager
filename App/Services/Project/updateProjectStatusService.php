<?php

namespace App\Services\Project;

use App\Interfaces\ProjectRepositoryInterface;
use App\Interfaces\TaskRepositoryInterface;

class updateProjectStatusService
{
    /** @var ProjectRepositoryInterface */
    private ProjectRepositoryInterface $projectRepositoryInterface;

    /** @var TaskRepositoryInterface */
    private TaskRepositoryInterface $taskRepositoryInterface;

    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface, TaskRepositoryInterface $taskRepositoryInterface)
    {
        $this->projectRepositoryInterface = $projectRepositoryInterface;
        $this->taskRepositoryInterface = $taskRepositoryInterface;
    }

    /**
     * @throws \Exception
     */
    public function exec(int $id, bool $manager) {
        $this->validateData($id, $manager);
        return $this->projectRepositoryInterface->updateProjectStatus($id);
    }

    /**
     * @throws \Exception
     */
    private function validateData(int $id, bool $manager) {
        if (empty($id) || $id == 0) {
            throw new \Exception("Enter a valid project id.");
        }

        if (!$manager) {
            throw new \Exception("The project can only be closed by a manager user.");
        }

        $projectData = $this->projectRepositoryInterface->getProjectById($id);

        if (!$projectData) {
            throw new \Exception("Project not found.");
        }

        $isTasksClosed = $this->taskRepositoryInterface->getClosedTasksStatusByProjectId($id);

        if (!$isTasksClosed) {
            throw new \Exception("All project tasks must be completed before closing the project.");
        }
    }
}