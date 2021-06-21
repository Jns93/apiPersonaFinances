<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Models\Installment;
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
                        ->with('category')->with('installments')->get();
    }

    public function getExpensesByMonth($userId, $date)
    {
        $carbon = new Carbon($date);
        return Expense::where('user_Id', '=', $userId)
                        ->with('category')
                        ->with('installments')
                        ->whereHas('installments', function ($query) use ($carbon) {
                            $query->whereYear('installments.due_date', '=', $carbon->year);
                            $query->whereMonth('installments.due_date', '=', $carbon->month);
                        })
                        ->get();
    }

    public function store(array $request)
    {
            $newexpense = new Expense();
            $newexpense->user_id = $request['user_id'];
            $newexpense->category_id = $request['category_id'];
            $newexpense->subcategory_id = $request['subcategory_id'];
            $newexpense->name = $request['name'];
            $newexpense->description = $request['description'];
            $newexpense->fl_essential = $request['fl_essential'];
            $newexpense->fl_fixed = $request['fl_fixed'];
            $newexpense->fl_split = $request['fl_split'];
            $newexpense->fl_installment = false;
            $newexpense->save();

            $newInstallment = new Installment();
            $newInstallment->expense_id = $newexpense->id;
            $newInstallment->number_installment = 1;
            $newInstallment->amount_installments = $request['installments'];
            $newInstallment->amount = str_replace(['.',','], ['','.'], $request['amount']);
            $newInstallment->due_date = $request['due_date'];
            $newInstallment->fl_pay = $request['fl_pay'];
            $newInstallment->save();
            return Expense::find($newexpense->id)->with('category')->with('installments')->orderBy('id', 'desc')->get()->first();
    }

    public function storeSplit($request) {
        $numberUsersForSplit = 2;
            for ($i = 1; $i <= $numberUsersForSplit; $i++) {
                $newexpense = new Expense();
                $newexpense->user_id = $i;
                $newexpense->category_id = $request['category_id'];
                $newexpense->subcategory_id = $request['subcategory_id'];
                $newexpense->name = $request['name'];
                $newexpense->description = $request['description'];
                $newexpense->fl_essential = $request['fl_essential'];
                $newexpense->fl_fixed = $request['fl_fixed'];
                $newexpense->fl_split = $request['fl_split'];
                $newexpense->fl_installment = false;
                $newexpense->save();

                $newInstallment = new Installment();
                $newInstallment->expense_id = $newexpense->id;
                $newInstallment->number_installment = 1;
                $newInstallment->amount_installments = $request['installments'];
                $newInstallment->amount = str_replace(['.',','], ['','.'], $request['amount'])/2;
                $newInstallment->due_date = $request['due_date'];
                $newInstallment->fl_pay = $request['fl_pay'];
                $newInstallment->save();
            }
            return Expense::find($newexpense->id)->with('category')->with('installments')->get()->first();
    }

    public function storeInstallment(array $request)
    {
        $carbon = new Carbon($request['due_date']);
        $newexpense = new Expense();
        $newexpense->user_id = $request['user_id'];
        $newexpense->category_id = $request['category_id'];
        $newexpense->subcategory_id = $request['subcategory_id'];
        $newexpense->name = $request['name'];
        $newexpense->description = $request['description'];
        $newexpense->fl_essential = $request['fl_essential'];
        $newexpense->fl_fixed = $request['fl_fixed'];
        $newexpense->fl_split = $request['fl_split'];
        $newexpense->fl_installment = true;
        $newexpense->save();
        for($i = 1; $i <= $request['installments']; $i++){
            $newInstallment = new Installment();
            $newInstallment->expense_id = $newexpense->id;
            $newInstallment->number_installment = $i;
            $newInstallment->amount_installments = $request['installments'];
            $newInstallment->amount = str_replace(['.',','], ['','.'], $request['amount']);
            $newInstallment->due_date = $i > 1 ? $request['due_date'] = $carbon->add(30, 'day') : $request['due_date'];
            $newInstallment->fl_pay = $request['fl_pay'];
            $newInstallment->save();
        }
        return Expense::find($newexpense->id)->with('category')->with('installments')->get()->first();
    }

    public function storeInstallmentSplit($request)
    {
        $numberUsersForSplit = 2;
            for ($i = 1; $i <= $numberUsersForSplit; $i++) {
                $newexpense = new Expense();
                $newexpense->user_id = $i;
                $newexpense->category_id = $request['category_id'];
                $newexpense->subcategory_id = $request['subcategory_id'];
                $newexpense->name = $request['name'];
                $newexpense->description = $request['description'];
                $newexpense->fl_essential = $request['fl_essential'];
                $newexpense->fl_fixed = $request['fl_fixed'];
                $newexpense->fl_split = $request['fl_split'];
                $newexpense->fl_installment = true;
                $newexpense->save();
                $this->splitInstallment($request, $newexpense->id);
            }
            return Expense::find($newexpense->id)->with('category')->with('installments')->get()->first();
    }

    public function splitInstallment($request, $expenseId)
    {
        $carbon = new Carbon($request['due_date']);
        for ($i = 1; $i <= $request['installments']; $i++) {
            $newInstallmentUser1 = new Installment();
            $newInstallmentUser1->expense_id = $expenseId;
            $newInstallmentUser1->number_installment = $i;
            $newInstallmentUser1->amount_installments = $request['installments'];
            $newInstallmentUser1->amount = str_replace(['.',','], ['','.'], $request['amount'])/2;
            $newInstallmentUser1->due_date = $i > 1 ? $request['due_date'] = $carbon->add(30, 'day') : $request['due_date'];
            $newInstallmentUser1->fl_pay = $request['fl_pay'];
            $newInstallmentUser1->save();
        }
    }

    public function delete($request)
    {
        $id = $request['id'];
        $expense_id = $request['expense_id'];

        $installment = Installment::find($id);
        $installment->delete();

        $expense = Expense::find($expense_id);
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
        // $expense->fl_split = $request['fl_split'];
        $expense->save();

        return Expense::find($expense->id)->with('category')->get()->first();
    }

    public function getTotalAmountExpensesByMonth($userId, $month)
    {
        $carbon = new Carbon($month);

        return $amount = Expense::whereMonth('due_date', '=', $carbon->month)
                        ->whereYear('due_date', '=', $carbon->year)
                        ->where('user_id', '=', $userId)
                        ->sum('amount');
    }

    public function getAverageExpenses($userId, $year)
    {
        $teste = DB::table('expenses')
                            ->select(
                                DB::raw('sum(amount) as amount'),
                                DB::raw('MONTH(due_date) month'))
                            ->whereYear('due_date', $year)
                            ->where('user_id', '=', $userId)
                            ->groupBy('month')
                            ->get()
                            ->avg('amount');

        if(empty($teste)){
           return $teste = 0;
        } else {
            return $teste;
        }
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
