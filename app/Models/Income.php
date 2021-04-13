<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'subcategory_id',
        'name',
        'amount',
        'installments',
        'date',
    ];

    protected $hidden = [

    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
