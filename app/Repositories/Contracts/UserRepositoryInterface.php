<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function registerUser(array $userData);
    public function getUser(int $userId);
}
