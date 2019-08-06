<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function view(User $user)
    {
        return $user->role != 'USER';
    }

    public function viewLogs(User $user)
    {
        return $user->role == 'ADMIN';
    }

    public function update(User $user)
    {
        return $user->role == 'ADMIN';
    }
}
