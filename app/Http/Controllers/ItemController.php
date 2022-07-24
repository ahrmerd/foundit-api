<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use App\Repositories\ItemRepository;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{

    private $repo;

    public function __construct(ItemRepository $repo)
    {
        $this->repo = $repo;
        $this->authorizeResource(Item::class, 'item', [
            'except' => ['index', 'show']
        ]);
    }
    public function index()
    {
        return $this->repo->index();
    }

    public function store(StoreItemRequest $request)
    {
        return $this->repo->create(array_merge($request->only(['name', 'description', 'category_id', 'location_id']), ['user_id' => auth()->id()]));
    }

    public function show($id)
    {
        return $this->repo->getById($id);
    }

    public function update(UpdateItemRequest $request, Item $item)
    {
        return $item->update($request->only(['name', 'description', 'category_id', 'location_id']));
    }

    public function destroy(Item $item)
    {
        return $item->delete();
    }
}
