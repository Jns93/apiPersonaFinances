<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Models\Installment;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

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
                        ->with(['installments' => function ($query) use ($carbon) {
                            $query->where(DB::raw('YEAR(due_date)'), $carbon->year);
                            $query->where(DB::raw('MONTH(due_date)'), $carbon->month);
                        }])
                        ->get();

        // $expenses = DB::table('expenses')
        // ->join('installments', 'expenses.id', '=', 'installments.expense_id')
        // ->join('categories', 'expenses.category_id', '=', 'categories.id')
        // ->select('expenses.*', 'installments.*', 'categories.name as category_name')
        // ->where('expenses.deleted_at', '=', null)
        // ->where('expenses.user_id', $userId)
        // ->whereYear('installments.due_date', $carbon->year)
        // ->whereMonth('installments.due_date', $carbon->month)
        // ->get();
        // return $expenses;


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
            // $newInstallment->amount = str_replace(['.',','], ['','.'], $request['amount']);
            $amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $request['amount']);
            $newInstallment->amount = floatval($amount);
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
                // $newInstallment->amount = str_replace(['.',','], ['','.'], $request['amount'])/2;
                $amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $request['amount']);
                $newInstallment->amount = floatval($amount)/2;
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
            // $newInstallment->amount = str_replace(['.',','], ['','.'], $request['amount']);
            $amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $request['amount']);
            $newInstallment->amount = floatval($amount);
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
            // $newInstallmentUser1->amount = str_replace(['.',','], ['','.'], $request['amount'])/2;
            $amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $request['amount']);
            $newInstallmentUser1->amount = floatval($amount)/2;
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
        $installmentUpdated = Installment::find($item['id']);
        $installmentUpdated->fl_pay = 1;
        $installmentUpdated->save();
        }
        return $installmentUpdated;
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

    public function getTotalAmountExpensesByMonth($userId, $month)
    {
        $carbon = new Carbon($month);

        $amount = DB::table('expenses')
                                ->join('installments', 'expense_id', '=', 'expenses.id')
                                ->whereMonth('installments.due_date', '=', $carbon->month)
                                ->whereYear('installments.due_date', '=', $carbon->year)
                                ->where('expenses.user_id', '=', $userId)
                                ->sum('installments.amount');

        return $amount;
    }

    public function getAverageExpenses($userId, $year)
    {
        $amount = DB::table('expenses')
                        ->join('installments', 'expense_id', '=', 'expenses.id')
                        ->select(
                                DB::raw('sum(installments.amount) as amount'),
                                DB::raw('MONTH(installments.due_date) month'))
                        ->whereYear('installments.due_date', '=', $year)
                        ->groupBy('month')
                        ->get()
                        ->avg('amount');

        if(empty($amount)){
           return $amount = 0;
        } else {
            return $amount;
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
