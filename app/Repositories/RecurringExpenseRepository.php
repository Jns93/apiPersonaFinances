<?php

namespace App\Repositories;

use App\Models\RecurringExpense;
use App\Models\Installment;
use App\Repositories\Contracts\RecurringExpenseRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class RecurringExpenseRepository implements RecurringExpenseRepositoryInterface
{
    public function index($userId)
    {
        return Expense::where('user_Id', '=', $userId)
                        ->with('category')->with('installments')->get();
    }

    public function store(array $request)
    {
        $expense = new RecurringExpense();
        $expense->user_id = $request['user_id'];
        $expense->category_id = $request['category_id'];
        $expense->subcategory_id = $request['subcategory_id'];
        $expense->name = $request['name'];
        $expense->description = $request['description'];
        $expense->fl_fixed = $request['fl_fixed'];
        $expense->fl_essential = $request['fl_essential'];
        $expense->expiration_day = $request['expiration_day'];
        $expense->amount = $request['amount'];
        $expense->save();

        return response($expense->toJson(), 201);
    }

    public function delete($id)
    {
        $expense = RecurringExpense::find($id);
        $expense->delete();

        return response($id, 204);
    }

    public function update(array $request)
    {
        try {
            $expense = Expense::find($request['id']);
            $expense->category_id = $request['category_id'];
            $expense->subcategory_id = $request['subcategory_id'];
            $expense->name = $request['name'];
            $expense->description = $request['description'];
            $expense->fl_essential = $request['fl_essential'];
            $expense->fl_fixed = $request['fl_fixed'];
            $expense->save();

            $installment = Installment::find($request['installment']['id']);
            $amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $request['installment']['amount']);
            $installment->amount = floatval($amount);
            $installment->due_date = $request['installment']['due_date'];
            $installment->fl_pay = $request['installment']['fl_pay'];
            $installment->save();

            return Expense::find($expense->id)->with('category')->get()->first();
        } catch (Error $e) {
            throw new Error($e);
        }
    }
}
