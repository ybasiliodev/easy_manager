<?php

namespace App\Models;

class Project
{
    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var \DateTime */
    private $endDate;

    /** @var boolean */
    private $status;

    /** @var User */
    private $user;

    public function __construct($id, $title, $endDate, $status, $user)
    {
        $this->id = $id;
        $this->title = $title;
        $this->endDate = $endDate;
        $this->status = $status ?: 0;
        $this->user = $user;
        $this->validate();
    }

    public function validate()
    {
        if ($this->id < 0) throw new \DomainException("User id must be an valid number.");
        if (empty($this->title)) throw new \DomainException("Title cannot be null.");
        if (empty($this->endDate)) throw new \DomainException("Project end date cannot be null.");
        if ($this->endDate->format('Y-m-d H:i:s') < date('Y-m-d h:i:s')) throw new \DomainException("The end date of the project must be greater than the current date");
        if (!$this->user instanceof User) throw new \DomainException("The project must have a valid user");
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
}