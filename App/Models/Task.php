<?php

namespace App\Models;

class Task
{
    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var \DateTime */
    private $endDate;

    /** @var boolean */
    private $status;

    /** @var User */
    private $user;

    /** @var Project */
    private $project;

    public function __construct($id, $title, $description, $endDate, $status, $user, $project)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->endDate = $endDate;
        $this->status = $status ?: 0;
        $this->user = $user;
        $this->project = $project;
        $this->validate();
    }

    public function validate()
    {
        if ($this->id < 0) throw new \DomainException("User id must be an valid number.");
        if (empty($this->title)) throw new \DomainException("Title cannot be null.");
        if (empty($this->endDate)) throw new \DomainException("Task end date cannot be null.");
        if ($this->endDate->format('Y-m-d H:i:s') < date('Y-m-d h:i:s')) throw new \DomainException("The end date of the task must be greater than the current date");
        if (!$this->user instanceof User) throw new \DomainException("The Task must have a valid user");
        if (!$this->project instanceof Project) throw new \DomainException("The task must belong to a valid project");
        if ($this->endDate->format('Y-m-d H:i:s') > $this->project->getEndDate()->format('Y-m-d H:i:s')) throw new \DomainException("The end date of the task must be lesser than the project end date");
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function changeTitle(string $title): void
    {
        $this->title = $title;
        $this->validate();
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function changeDescription(string $description): void
    {
        $this->description = $description;
        $this->validate();
    }

    /**
     * @return \DateTime
     */
    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     */
    public function changeEndDate(\DateTime $endDate): void
    {
        $this->endDate = $endDate;
        $this->validate();
    }

    /**
     * @return bool
     */
    public function getStatus(): mixed
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function changeStatus(mixed $status): void
    {
        $this->status = $status;
        $this->validate();
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function changeUser(User $user): void
    {
        $this->user = $user;
        $this->validate();
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function changeProject(Project $project): void
    {
        $this->project = $project;
        $this->validate();
    }
}