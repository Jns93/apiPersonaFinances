<?php

namespace App\Services;

use Carbon\Carbon;
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

    public function calculateBalanceGoalByMonth($request)
    {
        $month = $request['due_date'];

        $totalAmountIncomes = $this->incomeRepository->getTotalAmountIncomesByMonth($month);
        return ($totalAmountIncomes * 30) / 100;
    }

    public function calculateAverageIncomesByYear($request)
    {
        $year = $request['due_date'];

        return $averageIncomes = $this->incomeRepository->getAverageIncomes($year);
    }

    public function calculateAverageExpensesByYear($request)
    {
        $year = $request['due_date'];

        return $averageExpenses = $this->expenseRepository->getAverageExpenses($year);
    }

    public function calculateAveragePercentOfSavingByYear($request)
    {
        $year = $request['due_date'];

        return $averagePercent = $this->dashboardRepository->getAveragePercentOfSavingByYear($year);
    }

    public function getExpensesYearForChart($request)
    {
        $year = $request['due_date'];

       return $expensesForChart = $this->expenseRepository->getExpensesYearForChart($year);
    }

    public function getIncomesYearForChart($request)
    {
        $year = $request['due_date'];

       return $incomesForChart = $this->incomeRepository->getIncomesYearForChart($year);
    }

    public function getExpensesToBeDue()
    {
        return $expensesToBeDue = $this->expenseRepository->getExpensesToBeDue();
    }

    public function getIncomesToBeDue()
    {
        return $incomesToBeDue = $this->incomeRepository->getIncomesToBeDue();
    }
}
