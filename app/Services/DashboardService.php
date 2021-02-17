<?php

namespace App\Services;

use App\Repositories\Contracts\{
    DashboardRepositoryInterface,
    ExpenseRepositoryInterface,
    IncomeRepositoryInterface,
};

class DashboardService
{
    protected $dashboardRepository;

    public function __construct(
        DashboardRepositoryInterface $dashboardRepository,
        ExpenseRepositoryInterface $expenseRepository,
        IncomeRepositoryInterface $incomeRepository
        )
    {
        $this->dashboardRepository = $dashboardRepository;
        $this->expenseRepository = $expenseRepository;
        $this->incomeRepository = $incomeRepository;
    }

    public function getTotalAmountIncomesByMonth($request)
    {
        $month = $request['due_date'];

        return $totalAmountIncomes = $this->incomeRepository->getTotalAmountIncomesByMonth($month);
    }

    public function getTotalAmountExpensesByMonth($request)
    {
        $month = $request['due_date'];

        return $totalAmountExpenses = $this->expenseRepository->getTotalAmountExpensesByMonth($month);
    }

    public function calculateBalanceByMonth($request)
    {
        $month = $request['due_date'];

        $totalAmountIncomes = $this->incomeRepository->getTotalAmountIncomesByMonth($month);
        $totalAmountExpenses = $this->expenseRepository->getTotalAmountExpensesByMonth($month);

        return $balance = $totalAmountIncomes - $totalAmountExpenses;
    }

    public function calculatePercentageOfSavingsByMonth($request)
    {
        $month = $request['due_date'];

        $totalAmountIncomes = $this->incomeRepository->getTotalAmountIncomesByMonth($month);
        $totalAmountExpenses = $this->expenseRepository->getTotalAmountExpensesByMonth($month);

        $percentOfSavings = 0;
        if($totalAmountIncomes != null and $totalAmountExpenses != null) {
            $percentOfSavings = (($totalAmountIncomes - $totalAmountExpenses) * 100) / $totalAmountIncomes;
        }
        return $percentOfSavings;
    }
}
