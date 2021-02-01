<?php

namespace App\Repositories\Contracts;

interface CategoryRepositoryInterface
{
    public function getCategories();
    public function store(string $name);
    public function update(string $name, int $id);
    public function delete(int $id);
}
