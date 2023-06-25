<?php

namespace App\Services\User;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class AddUserService
{
    /** @var UserRepositoryInterface */
    private UserRepositoryInterface $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function exec($input): string
    {
        $newUser = new User(
            0,
            $input['username'],
            $input['cpf'],
            $input['email'],
            (int)$input['manager'],
        );

        return $this->userRepositoryInterface->addUser($newUser);
    }
}