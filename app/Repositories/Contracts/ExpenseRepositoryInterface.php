<?php

namespace App\Repositories\Contracts;

interface ExpenseRepositoryInterface
{
    public function getExpenses();
    public function getExpensesByMonth($date);
    public function store(array $request);
    public function storeInstallment(array $request);
    public function delete(int $id);
    public function pay(array $ids);
    public function update(array $request);
}
