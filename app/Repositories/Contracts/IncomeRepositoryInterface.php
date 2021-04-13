<?php

namespace App\Repositories\Contracts;

interface IncomeRepositoryInterface
{
    public function getIncomesByMonth($userId, $date);
    public function store(array $request);
    public function delete(int $id);
    public function pay(array $ids);
    public function update(array $request);
    public function getTotalAmountIncomesByMonth($userId, $month);
    public function getAverageIncomes($userId, $year);
    public function getIncomesYearForChart($userId, $year);
    public function getIncomesToBeDue($userId);
}
