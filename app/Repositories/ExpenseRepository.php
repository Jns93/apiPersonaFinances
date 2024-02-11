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
            $newexpense->description = isset($request['description'])? $request['description'] : '';
            $newexpense->fl_essential = isset($request['fl_essential'])? $request['fl_essential'] : 0;
            $newexpense->fl_fixed = isset($request['fl_fixed'])? $request['fl_fixed'] : 0;
            $newexpense->fl_split = isset($request['fl_split'])? $request['fl_split'] : 0;
            $newexpense->fl_installment = false;
            $newexpense->save();

            $newInstallment = new Installment();
            $newInstallment->expense_id = $newexpense->id;
            $newInstallment->number_installment = 1;
            $newInstallment->amount_installments = 1;
            $amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $request['amount']);
            $newInstallment->amount = floatval($amount);
            $newInstallment->due_date = $request['due_date'];
            $newInstallment->fl_pay = isset($request['fl_pay'])? $request['fl_pay'] : 0;
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
                $newexpense->description = isset($request['description'])? $request['description'] : '';
                $newexpense->fl_essential = isset($request['fl_essential'])? $request['fl_essential'] : 0;
                $newexpense->fl_fixed = isset($request['fl_fixed'])? $request['fl_fixed'] : 0;
                $newexpense->fl_split = $request['fl_split'];
                $newexpense->fl_installment = false;
                $newexpense->save();

                $newInstallment = new Installment();
                $newInstallment->expense_id = $newexpense->id;
                $newInstallment->number_installment = 1;
                $newInstallment->amount_installments = 1;
                $amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $request['amount']);
                $newInstallment->amount = floatval($amount)/2;
                $newInstallment->due_date = $request['due_date'];
                $newInstallment->fl_pay = isset($request['fl_pay'])? $request['fl_pay'] : 0;
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
        $newexpense->description = isset($request['description'])? $request['description'] : '';
        $newexpense->fl_essential = isset($request['fl_essential'])? $request['fl_essential'] : 0;
        $newexpense->fl_fixed = isset($request['fl_fixed'])? $request['fl_fixed'] : 0;
        $newexpense->fl_split = isset($request['fl_split'])? $request['fl_split'] : 0;
        $newexpense->fl_installment = true;
        $newexpense->save();
        for($i = 1; $i <= $request['installments']; $i++){
            $newInstallment = new Installment();
            $newInstallment->expense_id = $newexpense->id;
            $newInstallment->number_installment = $i;
            $newInstallment->amount_installments = $request['installments'];
            $amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $request['amount']);
            $newInstallment->amount = floatval($amount);
            $newInstallment->due_date = $i > 1 ? $request['due_date'] = $carbon->add(30, 'day') : $request['due_date'];
            $newInstallment->fl_pay = isset($request['fl_pay'])? $request['fl_pay'] : 0;
            $newInstallment->save();
        }
        return Expense::find($newexpense->id)->with('category')->with('installments')->find($newexpense->id);
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
                $newexpense->description = isset($request['description'])? $request['description'] : '';
                $newexpense->fl_essential = isset($request['fl_essential'])? $request['fl_essential'] : 0;
                $newexpense->fl_fixed = isset($request['fl_fixed'])? $request['fl_fixed'] : 0;
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
            $amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $request['amount']);
            $newInstallmentUser1->amount = floatval($amount)/2;
            $newInstallmentUser1->due_date = $i > 1 ? $request['due_date'] = $carbon->add(30, 'day') : $request['due_date'];
            $newInstallmentUser1->fl_pay = isset($request['fl_pay'])? $request['fl_pay']: 0;
            $newInstallmentUser1->save();
        }
    }

    public function delete($id)
    {
        $expense = Expense::find($id);
        if($expense) {
            $expense->installments()->delete();
            $expense->delete();
            return true;
        }
        return false;
    }

    public function pay(array $ids)
    {
        foreach($ids as $item) {
            $expense = Expense::with('installments')->find($item);
            if(!$expense) {
                return false;
            }
            foreach ($expense->installments as $installment) {
                $installment->update(['fl_pay' => 1]);
            }
            $updatedExpenses[] = $expense;
        }
        return $updatedExpenses;
    }

    public function update(array $request)
    {
        if(isset($request['installment'])) {
            $installment = $request['installment'];
            unset($request['installment']);

            if(isset($installment['amount'])) {
                $amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $installment['amount']);
                $installment['amount'] = floatval($amount);
            }
        }
        try {
            $expense = Expense::find($request['id']);
            $expense->update($request);

            if($installment) {
                Installment::find($installment['id'])->update($installment);
            }

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
                        ->where('user_Id', '=', $userId)
                        ->groupBy('month')
                        ->get()
                        ->avg('amount');

        if(empty($amount)){
           return $amount = 0;
        } else {
            return $amount;
        }
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

    public function getExpensestByCategoryChart($userId, $date)
    {
        $carbon = new Carbon($date);
        return DB::table('expenses')
                        ->select([DB::raw("sum(installments.amount) as amount"), DB::raw("categories.name as category")])
                        ->join('installments', 'expense_id', '=', 'expenses.id')
                        ->join('categories', 'category_id', '=', 'categories.id')
                        ->whereMonth('installments.due_date', '=', $carbon->month)
                        ->whereYear('installments.due_date', '=', $carbon->year)
                        ->where('expenses.user_id', '=', $userId)
                        // ->sum('installments.amount')
                        ->groupBy('category')
                        ->orderBy('amount', 'asc')
                        ->get();
    }

    public function getExpensestByMonthChart($userId, $date)
    {
        $carbon = new Carbon($date);
        return DB::table('expenses')
                        ->select([
                            DB::raw("sum(installments.amount) as amount"),
                            DB::raw("MONTH(installments.due_date) as month")
                        ])
                        ->join('installments', 'expense_id', '=', 'expenses.id')
                        ->whereYear('installments.due_date', '=', $carbon->year)
                        ->where('expenses.user_id', '=', $userId)
                        ->groupBy('month')
                        ->get();
    }

    public function getExpensesOrderByCategory($userId, $date)
    {
        //~DATA FROM TABLE
        $dataTable = DB::table('expenses')
                            ->select([
                                DB::raw("expenses.name as nome"),
                                DB::raw("categories.name as categoria"),
                                DB::raw("installments.amount as total"),
                                DB::raw("installments.due_date as data")
                            ])
                        ->join('installments', 'expense_id', '=', 'expenses.id')
                        ->join('categories', 'categories.id', '=', 'expenses.category_id')
                        ->whereMonth('installments.due_date', '=', $carbon->month)
                            ->whereYear('installments.due_date', '=', $carbon->year)
                            ->where('expenses.user_id', '=', $userId)
                            ->groupBy('categoria', 'nome', 'total', 'data')
                            ->get();

    }



}
