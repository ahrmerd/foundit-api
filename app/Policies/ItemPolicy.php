<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use App\Policies\Traits\AdminRights;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
{
    use HandlesAuthorization, AdminRights;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Item $item)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }


    public function update(User $user, Item $item)
    {
        return $user->id == $item->user_id;
    }

    public function delete(User $user, Item $item)
    {
        return $user->id == $item->user_id;
    }

    public function restore(User $user, Item $item)
    {
        //
    }

    public function forceDelete(User $user, Item $item)
    {
        //
    }
}
