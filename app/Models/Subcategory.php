<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    use SoftDeletes;

    protected $table = 'SubCategories';

    protected $fillable = [
        'category_id', 'name',
    ];

    protected $hidden = [
        'id',
    ];
}
