<?php

namespace App\Services\User;

use App\Interfaces\UserRepositoryInterface;

class GetAllUsersService
{
    /** @var UserRepositoryInterface */
    private UserRepositoryInterface $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function exec()
    {
        return $this->userRepositoryInterface->getAllUsers();
    }
}