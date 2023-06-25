<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAllUsers();
    public function getUserById(int $id);
    public function getUserByEmailAndDocument(string $email, string $cpf);
    public function addUser(User $user): string;
}