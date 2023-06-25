<?php

namespace App\Services\Task;

use App\Interfaces\ProjectRepositoryInterface;
use App\Interfaces\TaskRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Utils\DateFormat;

class addTaskService
{
    /** @var TaskRepositoryInterface */
    private TaskRepositoryInterface $taskRepositoryInterface;

    /** @var ProjectRepositoryInterface */
    private ProjectRepositoryInterface $projectRepositoryInterface;

    /** @var UserRepositoryInterface */
    private UserRepositoryInterface $userRepositoryInterface;

    /** @var DateFormat */
    private DateFormat $dateFormatUtil;

    public function __construct(
        TaskRepositoryInterface $taskRepositoryInterface,
        ProjectRepositoryInterface $projectRepositoryInterface,
        UserRepositoryInterface $userRepositoryInterface,
        DateFormat $dateFormatUtil
    )
    {
        $this->taskRepositoryInterface = $taskRepositoryInterface;
        $this->projectRepositoryInterface = $projectRepositoryInterface;
        $this->userRepositoryInterface = $userRepositoryInterface;
        $this->dateFormatUtil = $dateFormatUtil;
    }

    /**
     * @throws \Exception
     */
    public function exec($input, $manager)
    {
        $this->validateManager($manager);
        $user = $this->getValidTaskUser($input['user_id']);
        $project = $this->getValidProject($input['project_id'], $user);

        $input['id'] = $input['id'] ?? 0;
        $input['title'] = $input['title'] ?? null;
        $input['description'] = $input['description'] ?? null;
        $input['end_date'] = $input['end_date'] ? $this->dateFormatUtil->stringToDatetime($input['end_date']) : null;
        $input['status'] = $input['status'] ?? null;

        if ($input['id']) {
            $task = $this->getValidTask((int)$input['id'], $user, $project);
            if ($input['title']) $task->changeTitle($input['title']);
            if ($input['description']) $task->changeDescription($input['description']);
            if ($input['end_date']) $task->changeEndDate($input['end_date']);
            if ($input['title']) $task->changeTitle($input['title']);
            if ($input['user_id'] != $user->getId()) $task->changeUser($user);
            if ($input['project_id'] != $project->getId()) $task->changeProject($project);
            return $this->taskRepositoryInterface->updateTask($task);
        }

        $task = new Task(
            $input['id'],
            $input['title'],
            $input['description'],
            $input['end_date'],
            $input['status'],
            $user,
            $project
        );

        return $this->taskRepositoryInterface->addTask($task);
    }

    private function validateManager(bool $manager){
        if (!$manager) {
            throw new \DomainException("The project can only be created by a manager user.");
        }
    }

    private function getValidTaskUser(int $id): User
    {
        $userData = $this->userRepositoryInterface->getUserById($id);

        if (!$userData) {
            throw new \DomainException("User for task not found.");
        }

        if ($userData['manager']) {
            throw new \DomainException("The user to run the task cannot be a manager");
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

    /**
     * @throws \Exception
     */
    private function getValidTask(int $id, User $user, Project $project): Task
    {
        $taskData = $this->taskRepositoryInterface->getTaskById($id);

        if (!$taskData) {
            throw new \DomainException("Task not found.");
        }

        try {
            return new Task(
                $taskData['id'],
                $taskData['title'],
                $taskData['description'],
                $this->dateFormatUtil->stringToDatetime($taskData['end_date']),
                $taskData['status'],
                $user,
                $project
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}