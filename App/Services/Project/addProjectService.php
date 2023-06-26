<?php

namespace App\Services\Project;

use App\Interfaces\ProjectRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Project;
use App\Models\User;
use App\Utils\DateFormat;

class addProjectService
{
    /** @var ProjectRepositoryInterface */
    private ProjectRepositoryInterface $projectRepositoryInterface;

    /** @var UserRepositoryInterface */
    private UserRepositoryInterface $userRepositoryInterface;

    /** @var DateFormat */
    private DateFormat $dateFormatUtil;

    public function __construct(
        ProjectRepositoryInterface $projectRepositoryInterface,
        UserRepositoryInterface $userRepositoryInterface,
        DateFormat $dateFormatUtil
    )
    {
        $this->projectRepositoryInterface = $projectRepositoryInterface;
        $this->userRepositoryInterface = $userRepositoryInterface;
        $this->dateFormatUtil = $dateFormatUtil;
    }

    /**
     * @throws \Exception
     */
    public function exec($input, $loggedUserId, $manager)
    {
        $this->validateManager($manager);
        $user = $this->getValidProjectOwnerUser($loggedUserId);

        $input['id'] = $input['id'] ?? 0;
        $input['title'] = $input['title'] ?? null;
        $input['end_date'] = isset($input['end_date']) ? $this->dateFormatUtil->stringToDatetime($input['end_date']) : null;

        if ($input['id']) {
            $project = $this->getValidProject((int)$input['id'], $user);
            if ($input['title']) $project->changeTitle($input['title']);
            if ($input['end_date']) $project->changeEndDate($input['end_date']);
            if ($input['title']) $project->changeTitle($input['title']);
            return $this->projectRepositoryInterface->updateProject($project);
        }

        $project = new Project(
            $input['id'],
            $input['title'],
            $input['end_date'],
            1,
            $user,
        );

        return $this->projectRepositoryInterface->addProject($project);
    }

    private function validateManager(bool $manager){
        if (!$manager) {
            throw new \DomainException("The project can only be created by a manager user.");
        }
    }

    private function getValidProjectOwnerUser(int $loggedUserId): User
    {
        $userData = $this->userRepositoryInterface->getUserById($loggedUserId);

        if (!$userData) {
            throw new \DomainException("User for project not found.");
        }

        return new User($userData['id'], $userData['username'], $userData['cpf'], $userData['email'], $userData['manager']);
    }

    /**
     * @throws \Exception
     */
    private function getValidProject(int $id, User $user): Project
    {
        $projectData = $this->projectRepositoryInterface->getProjectById($id);

        if (!$projectData) {
            throw new \DomainException("Project not found");
        }

        try {
            return new Project(
                $projectData['id'],
                $projectData['title'],
                $this->dateFormatUtil->stringToDatetime($projectData['end_date']),
                $projectData['status'],
                $user
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}