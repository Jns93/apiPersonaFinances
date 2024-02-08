<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateSubcategory;
use App\Http\Resources\SubcategoryResource;
use App\Models\SubCategory;
use App\Services\SubcategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubcategoryController extends Controller
{
    protected $subcategoryService;

    public function __construct(SubcategoryService $subcategoryService)
    {
        $this->subcategoryService = $subcategoryService;
    }

    public function getAllSubcategories()
    {
        $subcategories = $this->subcategoryService->getAllSubcategories();
        return SubcategoryResource::collection($subcategories);
    }

    public function getSubcategoriesByCategory($categoryId)
    {
        $subcategories = $this->subcategoryService->getSubcategoriesByCategory($categoryId);

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

    public function delete($id)
    {
        $category = $this->subcategoryService->delete($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
