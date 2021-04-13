<?php

namespace App\Repositories;

use App\Repositories\Contracts\DashboardRepositoryInterface;
use Carbon\Carbon;
use App\Services\DashboardService;
use App\Repositories\Contracts\{
    ExpenseRepositoryInterface,
    IncomeRepositoryInterface,
};

class DashboardRepository implements DashboardRepositoryInterface
{
    public function __construct(
        ExpenseRepositoryInterface $expenseRepository,
        IncomeRepositoryInterface $incomeRepository
        )
    {
        $this->expenseRepository = $expenseRepository;
        $this->incomeRepository = $incomeRepository;
    }

    public function getAveragePercentOfSavingByYear($userId, $year)
    {
        $averageIncomes = $this->incomeRepository->getAverageIncomes($userId, $year);
        $averageExpenses = $this->expenseRepository->getAverageExpenses($userId, $year);

        $percentOfSaving = 0;
        if($averageIncomes != 0 and $averageExpenses != 0) {
            $percentOfSaving = (($averageIncomes - $averageExpenses) * 100 / $averageIncomes);
        }
        return number_format($percentOfSaving, 2, ',', '');
    }
}
