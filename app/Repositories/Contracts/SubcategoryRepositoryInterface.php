<?php

namespace App\Repositories\Contracts;

interface SubcategoryRepositoryInterface
{
    public function getAllSubcategories();
    public function getSubcategoriesByCategory(int $idCategory);
    public function storeDefaultSubcategory (int $idCategory);
    public function store(string $name, int $idCategory);
    public function update(string $name, int $id);
    public function delete(int $id);
}
