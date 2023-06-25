<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends Connection implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function getAllUsers(): bool|array
    {
        try {
            return $this->pdo
                ->query('SELECT * FROM user')
                ->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Error searching user.");
        }
    }

    /**
     * @throws \Exception
     */
    public function getUserById(int $id)
    {
        try {
            $statement = $this->pdo->prepare('SELECT * FROM user WHERE id = :id');

            $statement->execute([
                'id' => $id,
            ]);

            return $statement->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Error searching user.");
        }
    }

    /**
     * @throws \Exception
     */
    public function getUserByEmailAndDocument(string $email, string $cpf)
    {
        try {
            $statement = $this->pdo->prepare('SELECT * FROM user WHERE email = :email and cpf = :cpf');

            $statement->execute([
                'cpf' => $cpf,
                'email' => $email
            ]);

            return $statement->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Error searching user.");
        }
    }

    /**
     * @throws \Exception
     */
    public function addUser(User $user): string
    {
        try {
            $statement = $this->pdo->prepare(
                'INSERT INTO user (username, cpf, email, manager) VALUES (:username, :cpf, :email, :manager);'
            );

            $statement->execute([
                'username' => $user->getUsername(),
                'cpf' => $user->getCpf(),
                'email' => $user->getEmail(),
                'manager' => $user->isManager()
            ]);

            return "User successfully added.";
        } catch (\Exception $e) {
            throw new \Exception("Error inserting new user.");
        }
    }
}