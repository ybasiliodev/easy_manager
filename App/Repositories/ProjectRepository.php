<?php

namespace App\Repositories;

use App\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;

class ProjectRepository extends Connection implements ProjectRepositoryInterface
{

    /**
     * @throws \Exception
     */
    public function getAllProjects(): bool|array
    {
        try {
            return $this->pdo
                ->query('SELECT * FROM project')
                ->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Error searching projects");
        }
    }

    /**
     * @throws \Exception
     */
    public function getProjectById(int $id)
    {
        try {
            $statement = $this->pdo->prepare('SELECT * FROM project WHERE id = :id');

            $statement->execute(['id' => $id]);

            return $statement->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Error searching projects.");
        }
    }

    /**
     * @throws \Exception
     */
    public function addProject(Project $project): string
    {
        try {
            $statement = $this->pdo->prepare(
                'INSERT INTO project (title, end_date, status, user_id) 
                       VALUES (:title, :end_date, :status, :user_id);'
            );

            $statement->execute([
                'title' => $project->getTitle(),
                'end_date' => $project->getEndDate()->format('Y/m/d H:i:s'),
                'status' => $project->getStatus(),
                'user_id' => $project->getUser()->getId()
            ]);

            return "Project successfully added.";
        } catch (\Exception $e) {
            throw new \Exception("Error inserting new project.");
        }
    }

    /**
     * @throws \Exception
     */
    public function updateProject(Project $project): string
    {
        try {
            $statement = $this->pdo->prepare(
                'UPDATE project SET title = :title, end_date = :end_date, user_id = :user_id WHERE id = :id;'
            );

            $statement->execute([
                'id' => $project->getId(),
                'title' => $project->getTitle(),
                'end_date' => $project->getEndDate()->format('Y/m/d H:i:s'),
                'user_id' => $project->getUser()->getId()
            ]);

            return "Project successfully updated.";
        } catch (\Exception $e) {
            throw new \Exception("Error updating project.");
        }
    }

    /**
     * @throws \Exception
     */
    public function updateProjectStatus(int $id): string
    {
        try {
            $statement = $this->pdo->prepare(
                'UPDATE project SET status = 0 WHERE id = :id;'
            );

            $statement->execute([
                'id' => $id
            ]);

            return "Project status successfully updated.";
        } catch (\Exception $e) {
            throw new \Exception("Error updating project.");
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteProject(int $id): string
    {
        try {
            $statement = $this->pdo->prepare('DELETE FROM project WHERE id = :id;');
            $statement->execute(['id' => $id]);
            return "Project successfully deleted.";
        } catch (\Exception $e) {
            throw new \Exception("Error deleting project.");
        }
    }
}