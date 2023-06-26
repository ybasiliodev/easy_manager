<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;

class TaskRepository extends Connection implements TaskRepositoryInterface
{

    /**
     * @throws \Exception
     */
    public function getAllTasks(): bool|array
    {
        try {
            return $this->pdo
                ->query('SELECT * FROM task')
                ->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Error searching task.");
        }
    }

    /**
     * @throws \Exception
     */
    public function getTaskById(int $id)
    {
        try {
            $statement = $this->pdo->prepare('SELECT * FROM task WHERE id = :id');

            $statement->execute([
                'id' => $id,
            ]);

            return $statement->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Error searching task.");
        }
    }

    /**
     * @throws \Exception
     */
    public function getClosedTasksStatusByProjectId(int $projectId): bool
    {
        try {
            $statement = $this->pdo->prepare('SELECT count(id) FROM task WHERE project_id = :project_id');
            $statement->execute(['project_id' => $projectId]);
            $taskCount = $statement->fetchColumn();
            $statement = $this->pdo->prepare('SELECT count(id) FROM task WHERE project_id = :project_id AND status = 0');
            $statement->execute(['project_id' => $projectId]);
            $closedTaskCount = $statement->fetchColumn();

            return ($taskCount == $closedTaskCount);
        } catch (\Exception $e) {
            throw new \Exception("Error searching task.");
        }
    }

    /**
     * @throws \Exception
     */
    public function addTask(Task $task): string
    {
        try {
            $statement = $this->pdo->prepare(
                'INSERT INTO task (title, description, end_date, status, user_id, project_id) 
                       VALUES (:title, :description, :end_date, :status, :user_id, :project_id);'
            );

            $statement->execute([
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'end_date' => $task->getEndDate()->format('Y/m/d H:i:s'),
                'user_id' => $task->getUser()->getId(),
                'status' => $task->getStatus(),
                'project_id' => $task->getProject()->getId()
            ]);

            return "Task successfully added.";
        } catch (\Exception $e) {
            throw new \Exception("Error inserting new task.");
        }
    }

    /**
     * @throws \Exception
     */
    public function updateTask(Task $task): string
    {
        try {
            $statement = $this->pdo->prepare(
                'UPDATE task SET title = :title, description = :description, end_date = :end_date, 
                user_id = :user_id, project_id = :project_id WHERE id = :id;'
            );

            $statement->execute([
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'end_date' => $task->getEndDate()->format('Y-m-d H:i:s'),
                'user_id' => $task->getUser()->getId(),
                'project_id' => $task->getProject()->getId()
            ]);

            return "Task successfully updated.";
        } catch (\Exception $e) {
            throw new \Exception("Error updating task.");
        }
    }

    /**
     * @throws \Exception
     */
    public function updateTaskStatus(int $id, int $status): string
    {
        try {
            $statement = $this->pdo->prepare('UPDATE task SET status = :status WHERE id = :id;');

            $statement->execute([
                'id' => $id,
                'status' => $status
            ]);

            return "Task successfully updated.";
        } catch (\Exception $e) {
            throw new \Exception("Error updating task.");
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteTask(int $id): string
    {
        try {
            $statement = $this->pdo->prepare('DELETE FROM task WHERE id = :id;');
            $statement->execute(['id' => $id]);
            return "Task successfully deleted.";
        } catch (\Exception $e) {
            throw new \Exception("Error deleting task.");
        }
    }

    /**
     * @throws \Exception
     */
    public function endTask(int $id): string
    {
        try {
            $statement = $this->pdo->prepare(
                'UPDATE task SET status = 0 WHERE id = :id;'
            );

            $statement->execute([
                'id' => $id
            ]);

            return "Task successfully updated.";
        } catch (\Exception $e) {
            throw new \Exception("Error updating task.");
        }
    }
}