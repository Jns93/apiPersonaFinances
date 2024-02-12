<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateIncome;
use App\Http\Resources\IncomeResource;
use App\Services\IncomeService;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    protected $incomeService;

    public function __construct(IncomeService $incomeService)
    {
        $this->incomeService = $incomeService;
    }

    // public function index()
    // {
    //     return $expenses = $this->incomeService->getIncomes();
    // }

    public function getIncomesByMonth($userId, $dueDate)
    {
        $req = [
            'user_id' => $userId,
            'due_date' => $dueDate
        ];
        return $this->incomeService->getIncomesByMonth($req);
    }

    public function store(StoreUpdateIncome $request)
    {
        $expense = $this->incomeService->store($request->all());

        return $expense;
    }

    public function delete(Request $request)
    {
        $expense = $this->incomeService->delete($request->id);

        return new IncomeResource($expense);
    }

    public function pay(Request $request)
    {
        $expense = $this->incomeService->pay($request->all());

        return new IncomeResource($expense);
    }

    public function update(StoreUpdateIncome $request)
    {
        $expense = $this->incomeService->update($request->all());

        return $expense;
    }
}
