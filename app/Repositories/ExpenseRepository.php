<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    protected $table;

    public function __construct()
    {
        $this->table = 'categories';
    }

    public function getExpenses($userId)
    {
        return Expense::where('user_Id', '=', $userId)
                        ->with('category')->get();
    }

    public function getExpensesByMonth($userId, $date)
    {
        $carbon = new Carbon($date);
        // dd($carbon);
        return Expense::whereMonth('due_date', '=', $carbon->month)
                        ->where('user_Id', '=', $userId)
                        ->whereYear('due_date', '=', $carbon->year)
                        ->with('category')
                        ->get();
    }

    public function store(array $request)
    {
        if($request['fl_split']) {
            $newexpense = new Expense();
            $newexpense->user_id = 1;
            $newexpense->category_id = $request['category_id'];
            $newexpense->subcategory_id = $request['subcategory_id'];
            $newexpense->name = $request['name'];
            $newexpense->description = $request['description'];
            $newexpense->amount = str_replace(['.',','], ['','.'], $request['amount'])/2;
            $newexpense->installments = $request['installments'];
            $newexpense->fl_pay = $request['fl_pay'];
            $newexpense->fl_essential = $request['fl_essential'];
            $newexpense->fl_fixed = $request['fl_fixed'];
            $newexpense->due_date = $request['due_date'];
            $newexpense->fl_split = $request['fl_split'];
            $newexpense->save();

            $newexpense2 = new Expense();
            $newexpense2->user_id = 2;
            $newexpense2->category_id = $request['category_id'];
            $newexpense2->subcategory_id = $request['subcategory_id'];
            $newexpense2->name = $request['name'];
            $newexpense2->description = $request['description'];
            $newexpense2->amount = str_replace(['.',','], ['','.'], $request['amount'])/2;
            $newexpense2->installments = $request['installments'];
            $newexpense2->fl_pay = $request['fl_pay'];
            $newexpense2->fl_essential = $request['fl_essential'];
            $newexpense2->fl_fixed = $request['fl_fixed'];
            $newexpense2->due_date = $request['due_date'];
            $newexpense2->fl_split = $request['fl_split'];
            $newexpense2->save();

            return Expense::find($newexpense2->id)->with('category')->get()->first();
        }
        else {
            $newexpense = new Expense();
            $newexpense->user_id = 1;
            $newexpense->category_id = $request['category_id'];
            $newexpense->subcategory_id = $request['subcategory_id'];
            $newexpense->name = $request['name'];
            $newexpense->description = $request['description'];
            $newexpense->amount = str_replace(['.',','], ['','.'], $request['amount']);
            $newexpense->installments = $request['installments'];
            $newexpense->fl_pay = $request['fl_pay'];
            $newexpense->fl_essential = $request['fl_essential'];
            $newexpense->fl_fixed = $request['fl_fixed'];
            $newexpense->due_date = $request['due_date'];
            $newexpense->fl_split = $request['fl_split'];
            $newexpense->save();

            $expense = Expense::find($newexpense->id);

            return Expense::find($newexpense->id)->with('category')->orderBy('id', 'desc')->get()->first();
        }
    }

    public function storeInstallment(array $request)
    {
        $carbon = new Carbon($request['due_date']);

        if($request['fl_split']) {
            for($i = 1; $i <= $request['installments']; $i++){

                $newexpense = new Expense();
                $newexpense->user_id = 1;
                $newexpense->category_id = $request['category_id'];
                $newexpense->subcategory_id = $request['subcategory_id'];
                $newexpense->name = $request['name'];
                $newexpense->description = $request['description'] . " ({$i}/{$request['installments']})";
                $newexpense->amount = str_replace(['.',','], ['','.'], $request['amount'])/2;
                $newexpense->installments = 1;
                $newexpense->fl_pay = $request['fl_pay'];
                $newexpense->fl_essential = $request['fl_essential'];
                $newexpense->fl_fixed = $request['fl_fixed'];
                $i > 1 ? $newexpense->due_date = $request['due_date'] = $carbon->add(30, 'day') : $newexpense->due_date = $request['due_date'];
                $newexpense->fl_split = $request['fl_split'];
                $newexpense->save();

                $newexpense2 = new Expense();
                $newexpense2->user_id = 2;
                $newexpense2->category_id = $request['category_id'];
                $newexpense2->subcategory_id = $request['subcategory_id'];
                $newexpense2->name = $request['name'];
                $newexpense2->description = $request['description'] . " ({$i}/{$request['installments']})";
                $newexpense2->amount = str_replace(['.',','], ['','.'], $request['amount'])/2;
                $newexpense2->installments = 1;
                $newexpense2->fl_pay = $request['fl_pay'];
                $newexpense2->fl_essential = $request['fl_essential'];
                $newexpense2->fl_fixed = $request['fl_fixed'];
                $i > 1 ? $newexpense2->due_date = $request['due_date'] = $carbon->add(30, 'day') : $newexpense2->due_date = $request['due_date'];
                $newexpense2->fl_split = $request['fl_split'];
                $newexpense2->save();
            }
        }
        else{
            for($i = 1; $i <= $request['installments']; $i++){
                $newexpense = new Expense();
                $newexpense->user_id = 1;
                $newexpense->category_id = $request['category_id'];
                $newexpense->subcategory_id = $request['subcategory_id'];
                $newexpense->name = $request['name'];
                $newexpense->description = $request['description'] . " ({$i}/{$request['installments']})";
                $newexpense->amount = str_replace(['.',','], ['','.'], $request['amount']);
                $newexpense->installments = 1;
                $newexpense->fl_pay = $request['fl_pay'];
                $newexpense->fl_essential = $request['fl_essential'];
                $newexpense->fl_fixed = $request['fl_fixed'];
                $i > 1 ? $newexpense->due_date = $request['due_date'] = $carbon->add(30, 'day') : $newexpense->due_date = $request['due_date'];
                $newexpense->fl_split = $request['fl_split'];
                $newexpense->save();
            }
        }
        return $newexpense;
    }

    public function delete(int $id)
    {
        $expense = Expense::find($id);
        $expense->delete();

        return $expense;
    }

    public function pay(array $ids)
    {
        foreach($ids as $item) {
            $expenseUpdated = Expense::find($item['id']);
            $expenseUpdated->fl_pay = 1;
            $expenseUpdated->save();
        }
        return $expenseUpdated;
    }

    public function update(array $request)
    {
        $expense = Expense::find($request['id']);
        // $expense->user_id = 1;
        $expense->category_id = $request['category_id'];
        $expense->subcategory_id = $request['subcategory_id'];
        $expense->name = $request['name'];
        $expense->description = $request['description'];
        $expense->amount = str_replace(['.',','], ['','.'], $request['amount']);
        $expense->installments = $request['installments'];
        $expense->fl_pay = $request['fl_pay'];
        $expense->fl_essential = $request['fl_essential'];
        $expense->fl_fixed = $request['fl_fixed'];
        $expense->due_date = $request['due_date'];
        $expense->fl_split = $request['fl_split'];
        $expense->save();

        return Expense::find($expense->id)->with('category')->get()->first();
    }

    public function getTotalAmountExpensesByMonth($userId, $month)
    {
        $carbon = new Carbon($month);

        return Expense::whereMonth('due_date', '=', $carbon->month)
                        ->whereYear('due_date', '=', $carbon->year)
                        ->where('user_id', '=', $userId)
                        ->sum('amount');
    }

    public function getAverageExpenses($userId, $year)
    {
        return $teste = DB::table('expenses')
                            ->select(
                                DB::raw('sum(amount) as amount'),
                                DB::raw('MONTH(due_date) month'))
                            ->whereYear('due_date', $year)
                            ->where('user_id', '=', $userId)
                            ->groupBy('month')
                            ->get()
                            ->avg('amount');
    }

    public function getExpensesYearForChart($userId, $year)
    {
        return $expensesForChart = DB::table('expenses')
                                        ->select(
                                            DB::raw('sum(amount) as amount'),
                                            DB::raw("MONTH(due_date) as month")
                                        )
                                        ->whereYear('due_date', $year)
                                        ->groupBy(DB::raw("MONTH(due_date)"))
                                        ->get();
    }

    public function getExpensesToBeDue($userId)
    {
        $carbon = Carbon::now()->addDays(15);
        // dd($carbon);
        return Expense::where('due_date', '<', $carbon)
                        ->where('fl_Pay', '=', false)
                        ->where('user_id', '=', $userId)
                        ->with('category')
                        ->get();
    }
}
