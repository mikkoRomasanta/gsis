<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {

    }

    public function create(User $user)
    {
        return $user->role != 'USER';
    }

    public function update(User $user)
    {
        return $user->role != 'USER';
    }

    public function delete(User $user)
    {
        return $user->role != 'USER';
    }

    public function restore(User $user)
    {
        //
    }

    public function forceDelete(User $user)
    {
        //
    }
}
