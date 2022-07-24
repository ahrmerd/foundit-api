<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use App\Policies\Traits\AdminRights;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization, AdminRights;


    public function viewAny(User $user)
    {
        return true;
    }


    public function view(User $user, Message $message)
    {
        return $message->user_id == $user->id;
    }

    public function create(User $user, $conversationId)
    {
        return $user->conversations
            ->where('conversation_id', $conversationId)
            ->exists();
    }


    public function update(User $user, Message $message)
    {
        //
    }


    public function delete(User $user, Message $message)
    {
        return $message->user_id == $user->id;
    }

    public function restore(User $user, Message $message)
    {
        //
    }


    public function forceDelete(User $user, Message $message)
    {
        //
    }
}
