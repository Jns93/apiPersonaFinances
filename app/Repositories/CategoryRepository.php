<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $table;

    public function __construct()
    {
        $this->table = 'categories';
    }

    public function getCategories()
    {
        // return DB::table($this->table)->where('deleted_at', null)->get();
        return Category::all();
    }

    public function store(string $name)
    {
        $category = Category::create([ 'name' => $name]);

        return $category;
    }

    public function update(string $name, int $id)
    {
        $category = Category::find($id);
        $category->name = $name;
        $category->save();

        return $category;
    }

    public function delete(int $id)
    {
        $category = Category::find($id);
        $category->delete();
        $category->subcategories()->delete();
        return response()->json(null, 204);;
    }
}
