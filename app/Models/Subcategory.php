<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\SubcategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subcategory extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'SubCategories';

    protected $fillable = [
        'category_id', 'name',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function newFactory()
    {
        return SubcategoryFactory::new();
    }
}
