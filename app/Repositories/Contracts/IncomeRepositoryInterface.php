<?php

namespace App\Repositories\Contracts;

interface IncomeRepositoryInterface
{
    public function getIncomesByMonth($date);
    public function store(array $request);
    public function delete(int $id);
    public function pay(array $ids);
    public function update(array $request);
    public function getTotalAmountIncomesByMonth($month);
    public function getAverageIncomes($year);
    public function getIncomesYearForChart($year);
    public function getExpensesToBeDue();
}
