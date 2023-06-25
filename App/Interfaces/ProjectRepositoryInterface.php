<?php

namespace App\Interfaces;

use App\Models\Project;

interface ProjectRepositoryInterface
{
    public function getAllProjects();
    public function getProjectById(int $id);
    public function addProject(Project $project);
    public function updateProject(Project $project);
    public function updateProjectStatus(int $id);
    public function deleteProject(int $id);
}