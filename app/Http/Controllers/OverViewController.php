<?php

namespace App\Http\Controllers;

use App\Models\{Category, Item, State, User};
use Carbon\Carbon;
use Illuminate\Http\Request;

class OverViewController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response()->json([
            'data' => [
                'users_count' => $this->getNewUsersCount(),
                'items_count' => $this->getNewItemsCount(),
                'states' => $this->getStates(),
                'categories' => $this->getCategories()
            ]
        ]);
    }

    private function getNewUsersCount()
    {
        return User::query()->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())->count();
    }

    private function getNewItemsCount()
    {
        return Item::query()->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())->count();
    }

    private function getStates()
    {
        return State::query()->withCount('items')->orderBy('items_count', 'desc')->limit(5)->get();
    }

    private function getCategories()
    {
        return Category::query()->withCount('items')->orderBy('items_count', 'desc')->limit(5)->get();
    }
}
