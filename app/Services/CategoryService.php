<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories()
    {
        return $this->categoryRepository
                        ->getCategories();
    }

    public function store(array $category)
    {
        $name = $category['name'];

        $category = $this->categoryRepository->store($name);

        return $category;
    }

    public function update($category)
    {
        $id = $category['id'];
        $name = $category['name'];

        $category = $this->categoryRepository->update($name, $id);

        return $category;
    }

    public function delete($id)
    {
        $category = $this->categoryRepository->delete($id);
        return $category;
    }
}
