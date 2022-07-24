<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use App\Repositories\MessageRepository;

class MessageController extends Controller
{
    private $repo;

    public function __construct(MessageRepository $repo)
    {

        $this->repo = $repo;
        $this->authorizeResource(Message::class, null, [
            'except' => ['store']
        ]);
    }

    public function index()
    {
        $this->repo->index();
    }

    public function user()
    {
        $this->repo->index(Message::query()->where('user_id', auth()->id()));
    }

    public function store(StoreMessageRequest $request)
    {
        $this->authorize('create', [Message::class, $request->input('conversation_id')]);
        return $this->repo->create(array_merge($request->only(['message', 'conversation_id']), ['user_id' => auth()->id()]));
    }

    public function show($id)
    {
        return $this->repo->getById($id);
    }


    public function destroy(Message $message)
    {
        return $message->delete();
    }
}
