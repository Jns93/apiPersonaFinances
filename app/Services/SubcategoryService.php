<?php

namespace App\Services;

use App\Repositories\Contracts\SubcategoryRepositoryInterface;

class SubcategoryService
{
    protected $subcategoryRepository;

    public function __construct(SubcategoryRepositoryInterface $subcategoryRepository)
    {
        $this->subcategoryRepository = $subcategoryRepository;
    }

    public function getAllSubcategories()
    {
        return $this->subcategoryRepository
                        ->getAllSubcategories();
    }

    public function getSubcategoriesByCategory($idCategory)
    {
        return $this->subcategoryRepository
                        ->getSubcategoriesByCategory($idCategory);
    }

    public function storeDefaultSubcategory ($idCategory)
    {
        $this->subcategoryRepository->storeDefaultSubcategory($idCategory);
    }

    public function store(array $subcategory)
    {

        $name = $subcategory['name'];
        $idcategory = $subcategory['category_id'];

        $subcategory = $this->subcategoryRepository->store($name, $idcategory);

        return $subcategory;
    }

    public function update($subcategory)
    {
        $id = $subcategory['id'];
        $name = $subcategory['name'];

        $subcategory = $this->subcategoryRepository->update($name, $id);

        return $subcategory;
    }

    public function delete($id)
    {
        $subcategory = $this->subcategoryRepository->delete($id);

        return $subcategory;
    }
}
