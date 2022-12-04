<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\RecurringExpenseFactory;

class RecurringExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'amount',
        'expiration_day',
        'user_id',
        'fl_essential',
        'description',
        'fl_fixed',
        'type'
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    protected static function newFactory()
    {
        return RecurringExpenseFactory::new();
    }
}
