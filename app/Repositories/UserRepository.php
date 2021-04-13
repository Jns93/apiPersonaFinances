<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    protected $entity;

    public function __construct(User $user)
    {
        $this->entity = $user;
    }

    public function registerUser(array $userData)
    {
        $userData['password'] = bcrypt($userData['password']);
        return $this->entity->create($userData);
    }

    public function getUser(int $id)
    {

    }

}
