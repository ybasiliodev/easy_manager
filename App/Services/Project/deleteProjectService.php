<?php

namespace App\Services\Project;

use App\Interfaces\ProjectRepositoryInterface;

class deleteProjectService
{
    /** @var ProjectRepositoryInterface */
    private ProjectRepositoryInterface $projectRepositoryInterface;

    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface)
    {
        $this->projectRepositoryInterface = $projectRepositoryInterface;
    }

    /**
     * @throws \Exception
     */
    public function exec(int $id, bool $manager) {
        $this->validateData($id, $manager);
        return $this->projectRepositoryInterface->deleteProject($id);
    }

    /**
     * @throws \Exception
     */
    private function validateData(int $id, bool $manager) {
        if (empty($id) || $id == 0) {
            throw new \Exception("Enter a valid project id.");
        }

        if (!$manager) {
            throw new \Exception("The project can only be deleted by a manager user.");
        }
    }
}