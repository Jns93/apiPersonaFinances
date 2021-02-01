<?php

namespace App\Services;

use App\Repositories\Contracts\ExpenseRepositoryInterface;

class ExpenseService
{
    protected $expenseRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function getExpenses()
    {
        return $expenses = $this->expenseRepository->getExpenses();
    }

    public function getExpensesByMonth($request)
    {
        $date = $request['due_date'];
 
        return $expenses = $this->expenseRepository->getExpensesByMonth($date);
    }


    public function store(array $request)
    {
       return $expense = $this->expenseRepository->store($request);
    }

    public function storeInstallment(array $request)
    {
        return $expense = $this->expenseRepository->storeInstallment($request);
    }

    public function delete($id)
    {
        $expense = $this->expenseRepository->delete($id);

        return $expense;
    }

    public function pay(array $ids)
    {
        return $this->expenseRepository->pay($ids);
    }

    public function update(array $request)
    {
        return $this->expenseRepository->update($request);
    }
}
