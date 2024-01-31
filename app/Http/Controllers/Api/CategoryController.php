<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategory;
use App\Http\Resources\CategoryReource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()

    {
        // dd($this->categoryService->getCategories());
        // return $this->categoryService->getCategories();
        return CategoryReource::collection($this->categoryService->getCategories());
    }

    public function update(StoreUpdateCategory $request)
    {
        $category = $this->categoryService->update($request);

        return new CategoryReource($category);
    }

    public function store(StoreUpdateCategory $request)
    {
        $category = $this->categoryService->store($request->all());

        return new CategoryReource($category);
    }

    public function delete(Request $request)
    {
        $response = $this->categoryService->delete($request->id);
        return $response;
    }

}
