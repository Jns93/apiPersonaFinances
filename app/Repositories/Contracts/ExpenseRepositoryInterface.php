<?php

namespace App\Repositories\Contracts;

interface ExpenseRepositoryInterface
{
    public function getExpenses($userId);
    public function getExpensesByMonth($userId, $date);
    public function store(array $request);
    public function storeInstallment(array $request);
    public function delete(int $id);
    public function pay(array $ids);
    public function update(array $request);
    public function getTotalAmountExpensesByMonth($userId, $month);
    public function getAverageExpenses($userId, $year);
    public function getExpensesYearForChart($userId, $year);
    public function getExpensesToBeDue($userId);
}
