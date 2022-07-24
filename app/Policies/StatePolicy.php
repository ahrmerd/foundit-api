<?php

namespace App\Policies;

use App\Models\State;
use App\Models\User;
use App\Policies\Traits\AdminRights;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatePolicy
{
    use HandlesAuthorization, AdminRights;


    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, State $state)
    {
        //
    }


    public function create(User $user)
    {
        //
    }

    public function update(User $user, State $state)
    {
        //
    }

    public function delete(User $user, State $state)
    {
        //
    }

    public function restore(User $user, State $state)
    {
        //
    }

    public function forceDelete(User $user, State $state)
    {
        //
    }
}
