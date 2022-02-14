<?php

namespace App\Services;

use App\Repositories\Contracts\RecurringExpenseRepositoryInterface;

class RecurringExpenseService
{
    protected $repository;

    public function __construct(RecurringExpenseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($request)
    {
        $userId = $request['id'];

        return $expenses = $this->expenseRepository->getExpenses($userId);
    }

    public function store(array $request)
    {
        return $this->repository->store($request);
    }

    public function delete($request)
    {
        $expense = $this->expenseRepository->delete($request);

        return $expense;
    }

    public function update(array $request)
    {
        return $this->expenseRepository->update($request);
    }
}
