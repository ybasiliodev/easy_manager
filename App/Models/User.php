<?php

namespace App\Models;

class User
{
    /** @var int */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $cpf;

    /** @var string */
    private $email;

    /** @var int */
    private $manager;

    public function __construct($id, $username, $cpf, $email, $isManager)
    {
        $this->id = $id;
        $this->cpf = $cpf;
        $this->username = $username;
        $this->email = $email;
        $this->manager = $isManager ?: 0;
        $this->validate();
    }

    private function validate(): void {
        if ($this->id < 0) throw new \DomainException("User id must be an valid number.");
        if (empty($this->cpf)) throw new \DomainException("CPF id cannot be null.");
        if (empty($this->username)) throw new \DomainException("Username cannot be null.");
        if (empty($this->email)) throw new \DomainException("Email cannot be null.");
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
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function changeUsername(string $username): void
    {
        $this->username = $username;
        $this->validate();
    }

    /**
     * @return string
     */
    public function getCpf(): string
    {
        return $this->cpf;
    }

    /**
     * @param string $cpf
     */
    public function changeCpf(string $cpf): void
    {
        $this->cpf = $cpf;
        $this->validate();
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function changeEmail(string $email): void
    {
        $this->email = $email;
        $this->validate();
    }

    /**
     * @return int
     */
    public function isManager(): int
    {
        return $this->manager;
    }

    /**
     * @param int $manager
     */
    public function changeManager(int $manager): void
    {
        $this->manager = $manager;
        $this->validate();
    }

}