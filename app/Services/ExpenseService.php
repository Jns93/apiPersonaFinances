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

    public function getExpenses($request)
    {
        $userId = $request['id'];

        return $expenses = $this->expenseRepository->getExpenses($userId);
    }

    public function getExpensesByMonth($request)
    {
        $date = $request['due_date'];
        $userId = $request['userId'];

        return $expenses = $this->expenseRepository->getExpensesByMonth($userId, $date);
    }


    public function store(array $request)
    {
        if($request['fl_split']) {
            return $this->expenseRepository->storeSplit($request);
        } else {
            return $this->expenseRepository->store($request);
        }
    }

    public function storeInstallment(array $request)
    {
        if($request['fl_split']) {
            return $this->expenseRepository->storeInstallmentSplit($request);
        } else {
            return $this->expenseRepository->storeInstallment($request);
        }
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
