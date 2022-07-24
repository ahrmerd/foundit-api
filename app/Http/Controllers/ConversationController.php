<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConversationRequest;
use App\Http\Resources\BaseResource;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Conversation::class, 'conversation');
    }

    public function index()
    {
        return BaseResource::collection(Conversation::query()->with('users')->get());
    }

    public function user()
    {
        return BaseResource::collection(auth()->user()->conversations()->with('users')->get());
    }

    public function store(StoreConversationRequest $request)
    {
        $conversations = auth()->user()->conversations()->whereHas('users', function ($query) use ($request) {
            $query->where('users.id', '=', $request->input('user_id'));
        });

        if ($conversations->exists()) {
            return $conversations->first()->id;
        }
        $conversation = Conversation::query()->create([]);
        auth()->user()->conversations()->syncWithoutDetaching($conversation->id);
        User::query()->find($request->input('user_id'))->conversations()->syncWithoutDetaching($conversation->id);
        return $conversation->id;
    }


    public function show(Conversation $conversation)
    {
        return $conversation->messages;
    }

    public function destroy(Conversation $conversation)
    {
        $conversation->delete();
    }
}
