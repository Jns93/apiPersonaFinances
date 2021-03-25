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
    public function getTotalAmountExpensesByMonth($month);
    public function getAverageExpenses($year);
    public function getExpensesYearForChart($year);
    public function getExpensesToBeDue();
}
