<?php

namespace App\Repositories;

use App\Models\RecurringExpense;
use App\Models\Installment;
use App\Repositories\Contracts\RecurringExpenseRepositoryInterface;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Repositories\Contracts\IncomeRepositoryInterface;
use Carbon\Carbon;
use App\Models\RecurringHistory;

class RecurringExpenseRepository implements RecurringExpenseRepositoryInterface
{
    protected $expenseRepository;
    protected $incomeRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository, IncomeRepositoryInterface $incomeRepository)
    {
        $this->expenseRepository = $expenseRepository;
        $this->incomeRepository = $incomeRepository;
    }

    public function index($userId)
    {
        $res = RecurringExpense::where('user_Id', '=', $userId)
                        ->with('category')->get();
        return $res;
    }

    public function store($request)
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
        $expense->amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $request['amount']);
        $expense->type = $request['type'];
        $expense->save();

        return response($expense->toJson(), 201);
    }

    public function delete($id)
    {
        $expense = RecurringExpense::find($id['id']);
        $expense->delete();

        return $id;
    }

    public function update(array $request)
    {
        try {
            $expense = RecurringExpense::find($request['id']);
            $expense->category_id = $request['category_id'];
            $expense->subcategory_id = $request['subcategory_id'];
            $expense->name = $request['name'];
            $expense->description = $request['description'];
            $expense->fl_essential = $request['fl_essential'];
            $expense->fl_fixed = $request['fl_fixed'];
            $expense->save();

            $response = RecurringExpense::find($expense->id)->with('category')->get()->first();
            return response($response->toJson(), 200);
        } catch (Error $e) {
            throw new Error($e);
        }
    }

    public function execute()
    {
        $expensesRecurring = RecurringExpense::all();
        $expenses = $this->storeExpenses($expensesRecurring);
        $incomes = $this->storeIncomes($expensesRecurring);

        $history = new RecurringHistory();
        $history->save();
        return response('Sucesso', 201);
    }

    public function needToRun()
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');
        $res = RecurringHistory::whereYear('created_at', '=', $year)
                                ->whereMonth('created_at', '=', $month)
                                ->get();

        if(isset($res[0])) return false;
        return true;
    }

    public function storeExpenses($recurrings)
    {
        $response = [];
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');

        foreach($recurrings as $item) {
            $date = Carbon::create($year, $month, $item->expiration_day);
            if($item->type == 'Despesa') {
                $expense['user_id'] = $item->user_id;
                $expense['category_id'] = $item->category_id;
                $expense['subcategory_id'] = $item->subcategory_id;
                $expense['name'] = $item->name;
                $expense['description'] = $item->description;
                $expense['fl_essential'] = $item->fl_essential;
                $expense['fl_fixed'] = $item->fl_fixed;
                $expense['fl_split'] = 0;
                $expense['installments'] = 1;
                $expense['amount'] = str_replace(['.'], [','], $item->amount);

                $expense['due_date'] = $date;
                $expense['fl_pay'] = false;
                $res = $this->expenseRepository->store($expense);
                array_push($response, $res);
            }

        }
    }

    public function storeIncomes($recurrings)
    {
        $response = [];
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');

        foreach($recurrings as $item) {
            $date = Carbon::create($year, $month, $item->expiration_day);
            if($item->type == 'Renda') {
                $income['user_id'] = $item->user_id;
                $income['category_id'] = $item->category_id;
                $income['subcategory_id'] = $item->subcategory_id;
                $income['name'] = $item->name;
                $income['description'] = $item->description;
                $income['fl_essential'] = $item->fl_essential;
                $income['fl_fixed'] = $item->fl_fixed;
                $income['fl_split'] = 0;
                $income['installments'] = 1;
                $income['amount'] = str_replace(['.'], [','], $item->amount);
                $income['due_date'] = $date;
                $income['fl_pay'] = false;
                $res = $this->incomeRepository->store($income);
                array_push($response, $res);
            }

        }
    }
}
