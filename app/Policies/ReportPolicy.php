<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use App\Policies\Traits\AdminRights;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization, AdminRights;

    public function viewAny(User $user)
    {
    }



    public function view(User $user, Report $report)
    {
    }

    public function create(User $user)
    {
        return true;
    }


    public function update(User $user, Report $report)
    {
        //
    }


    public function delete(User $user, Report $report)
    {
        //
    }


    public function restore(User $user, Report $report)
    {
        //
    }


    public function forceDelete(User $user, Report $report)
    {
        //
    }
}
