<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateSubcategory;
use App\Http\Resources\SubcategoryResource;
use App\Models\SubCategory;
use App\Services\SubcategoryService;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    protected $subcategoryService;

    public function __construct(SubcategoryService $subcategoryService)
    {
        $this->subcategoryService = $subcategoryService;
    }

    public function getAllSubcategories()
    {
        return SubcategoryResource::collection($this->subcategoryService->getAllSubcategories());
    }

    public function getSubcategoriesByCategory(StoreUpdateSubcategory $request)
    {
        $subcategories = $this->subcategoryService->getSubcategoriesByCategory($request->id_category);

        return SubcategoryResource::collection($subcategories);
    }

    public function update(StoreUpdateSubcategory $request)
    {
        $subcategory = $this->subcategoryService->update($request);

        return new SubcategoryResource($subcategory);
    }

    public function store(StoreUpdateSubcategory $request)
    {
        $subcategory = $this->subcategoryService->store($request->all());

        return new SubcategoryResource($subcategory);
    }

    public function delete(Request $request)
    {
        $category = $this->subcategoryService->delete($request->id);

        return new SubcategoryResource($category);
    }
}
