<?php

namespace App\Policies\Traits;

use App\Models\User;

trait AdminRights
{
    public function before(User $user, $abilty)
    {
        if ($user->isAdmin()) return true;
    }
}
