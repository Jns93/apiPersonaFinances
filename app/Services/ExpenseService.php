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

        $expenses = $this->expenseRepository->getExpensesByMonth($userId, $date);
        $expenses = $this->removeExpensesWithoutInstallments($expenses);
        return $expenses;
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

    public function delete($request)
    {
        $expense = $this->expenseRepository->delete($request);

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

    public function removeExpensesWithoutInstallments($queryResult)
    {
        foreach($queryResult as $key => $item) {
            if(!$item->installments->count()) {
                $queryResult->pull($key);
            }
        }
        return $queryResult->toArray();
    }
}
