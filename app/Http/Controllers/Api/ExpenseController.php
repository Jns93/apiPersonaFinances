<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateExpense;
use App\Http\Resources\ExpenseResource;
use App\Http\Resources\InstallmentResource;
use App\Services\ExpenseService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index($userId)
    {
        return $expenses = $this->expenseService->getExpenses($userId);
    }

    public function getExpensesByMonth(Request $request)
    {
        return $expenses = $this->expenseService->getExpensesByMonth($request);
    }

    public function store(StoreUpdateExpense $request)
    {
        if(isset($request['installments']) && $request['installments'] > 1) {
            $expense = $this->expenseService->storeInstallment($request->all());
        }
        else {
            $expense = $this->expenseService->store($request->all());
        }

        return response()->json($expense, 201);
    }

    public function delete($expenseId)
    {
        $response = $this->expenseService->delete($expenseId);
        if(!$response) {
            return response()->json(['message' => 'Despesa não encontrada'], 404);
        }
        return response()->noContent();
    }

    public function pay(Request $request)
    {
        $response = $this->expenseService->pay($request->all());
        if(!$response) {
            return response()->json(['message' => 'Despesa não encontrada'], 404);
        }
        return response()->json($response, 200);
    }

    public function update(Request $request)
    {
        $expense = $this->expenseService->update($request->all());

        return $expense;
    }
}
