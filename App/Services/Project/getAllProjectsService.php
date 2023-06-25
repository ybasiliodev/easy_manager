<?php

namespace App\Services\Project;

use App\Interfaces\ProjectRepositoryInterface;

class getAllProjectsService
{
    /** @var ProjectRepositoryInterface */
    private ProjectRepositoryInterface $projectRepositoryInterface;

    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface)
    {
        $this->projectRepositoryInterface = $projectRepositoryInterface;
    }

    public function exec()
    {
        return $this->projectRepositoryInterface->getAllProjects();
    }
}