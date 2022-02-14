<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use SoftDeletes, HasFactory;

    protected $table ='categories';

    protected $fillable = [
        'name'
    ];

    public function subcategories()
    {
        return $this->hasMany('App\Models\SubCategory', 'category_id', 'id');
    }

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
