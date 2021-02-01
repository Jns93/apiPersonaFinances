<?php

namespace App\Observers;

use App\Models\Expense;
use App\Services\ExpenseService;
use Carbon\Carbon;

class ExpenseObserver
{
    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function created(Expense $expense)
    {
        $carbon = new Carbon($expense->date);
        $installments = $expense->installments;

        if ($installments > 1)
        {
            for ($i = 1; $i <= $installments; $i++){
                $newexpense = new Expense();
                $newexpense->user_id = 1;
                $newexpense->category_id = $expense->category_id;
                $newexpense->subcategory_id = $expense->subcategory_id;
                $newexpense->name = $expense->name . " (parcela {$i}/{$installments})";
                $newexpense->amount = $expense->amount;
                $newexpense->installments = 1;
                $newexpense->pay = 0;
                $i >= 2 ? $newexpense->date = $expense->date = $carbon->add(30, 'day') : $newexpense->date = $expense->date;
                $newexpense->save();
            }
        }
    }
}
