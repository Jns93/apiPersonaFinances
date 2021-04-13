<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'amount',
        'installments',
        'date',
        'user_id',
    ];

    protected $hidden = [

    ];

    public function category()
    {
        // return $this->hasOne('App\Models\Category', 'id');
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
