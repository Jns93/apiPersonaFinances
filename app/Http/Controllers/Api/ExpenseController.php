<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateExpense;
use App\Http\Resources\ExpenseResource;
use App\Services\ExpenseService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index()
    {
        return $expenses = $this->expenseService->getExpenses();
    }

    public function getExpensesByMonth(Request $request)
    {
        return $expenses = $this->expenseService->getExpensesByMonth($request);
    }

    public function store(StoreUpdateExpense $request)
    {
        if($request->installments > 1) {
            $expense = $this->expenseService->storeInstallment($request->all());
        }
        else {
            $expense = $this->expenseService->store($request->all());
        }

        return $expense;
    }

    public function delete(Request $request)
    {
        $expense = $this->expenseService->delete($request->id);

        return new ExpenseResource($expense);
    }

    public function pay(Request $request)
    {
        $expense = $this->expenseService->pay($request->all());

        return new ExpenseResource($expense);
    }

    public function update(StoreUpdateExpense $request)
    {
        $expense = $this->expenseService->update($request->all());

        return $expense;
    }
}