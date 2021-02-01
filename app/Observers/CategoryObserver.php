<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\SubcategoryService;

class CategoryObserver
{
    protected $subcategoryService;

    public function __construct(SubcategoryService $subcategoryService)
    {
        $this->subcategoryService = $subcategoryService;
    }

    /**
     * Handle the category "created" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function created(Category $category)
    {
        $idcategory = $category->id;
        if ($idcategory > 10)
        {
            $this->subcategoryService->storeDefaultSubcategory($idcategory);
        }
    }

}
