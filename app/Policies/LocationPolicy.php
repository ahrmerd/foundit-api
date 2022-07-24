<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;
use App\Policies\Traits\AdminRights;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
{
    use HandlesAuthorization, AdminRights;


    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Location $location)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Location $location)
    {
        //
    }

    public function delete(User $user, Location $location)
    {
        //
    }

    public function restore(User $user, Location $location)
    {
        //
    }

    public function forceDelete(User $user, Location $location)
    {
        //
    }
}
