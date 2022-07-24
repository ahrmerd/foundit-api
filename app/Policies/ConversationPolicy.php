<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
use App\Policies\Traits\AdminRights;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversationPolicy
{
    use HandlesAuthorization, AdminRights;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Conversation $conversation)
    {
        return $this->exists($conversation, $user);
    }

    public function create(User $user)
    {
        return true;
    }



    public function update(User $user, Conversation $conversation)
    {
    }

    public function delete(User $user, Conversation $conversation)
    {
        return $this->exists($conversation, $user);
    }

    public function restore(User $user, Conversation $conversation)
    {
        //
    }

    public function forceDelete(User $user, Conversation $conversation)
    {
        //
    }

    private function exists(Conversation $conversation, User $user)
    {
        return $conversation->users()
            ->where('user_id', $user->id)
            ->exists();
    }
}
