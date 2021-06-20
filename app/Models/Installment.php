<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $fillable = [
        'number_installment',
        'amount_installments',
        'amount',
        'due_date',
        'fl_pay',
    ];

    protected $hidden = [

    ];


}
