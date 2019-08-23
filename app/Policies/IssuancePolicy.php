<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IssuancePolicy
{
    use HandlesAuthorization;

     public function modify(User $user){
        return $user->role != 'USER';
     }
}
