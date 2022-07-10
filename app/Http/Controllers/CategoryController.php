<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{

    private $repo;

    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return $this->repo->index();
    }

    public function store(StoreCategoryRequest $request)
    {
        return $this->repo->create($request->only(['name', 'description']));
    }

    public function show($id)
    {
        return $this->repo->getById($id);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        return $category->update($request->only(['name', 'description']));
    }

    public function destroy(Category $category)
    {
        return $category->delete();
    }
}
