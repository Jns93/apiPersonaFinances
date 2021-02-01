<?php

namespace App\Repositories;

use App\Models\Subcategory;
use App\Repositories\Contracts\SubcategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SubcategoryRepository implements SubcategoryRepositoryInterface
{
    protected $table;

    public function __construct()
    {
        $this->table = 'subcategories';
    }

    public function getAllSubcategories()
    {
        return Subcategory::all();
    }

    public function getSubcategoriesByCategory(int $idCategory)
    {
        return DB::table($this->table)
                    ->where('deleted_at', null)
                    ->where('category_id', $idCategory)
                    ->select('subcategories.*')
                    ->get();

    }

    public function storeDefaultSubcategory (int $idCategory)
    {
        SubCategory::create(
            [
                'category_id' => $idCategory,
                'name' => 'Sem subcategoria'
            ]
        );
    }

    public function store(string $name, int $idCategory)
    {
        // $subcategory = Subcategory::create([ 'name' => $name]);

        $subcategory = new SubCategory();
        $subcategory->category_id = $idCategory;
        $subcategory->name = $name;
        $subcategory->save();

        return $subcategory;
    }

    public function update(string $name, int $id)
    {
        $subcategory = Subcategory::find($id);
        $subcategory->name = $name;
        $subcategory->save();

        return $subcategory;
    }

    public function delete(int $id)
    {
        $subcategory = Subcategory::find($id);
        $subcategory->delete();

        return $subcategory;
    }
}
