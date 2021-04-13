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
        $userId = $request['userId'];

        return $totalAmountIncomes = $this->incomeRepository->getTotalAmountIncomesByMonth($userId, $month);
    }

    public function getTotalAmountExpensesByMonth($request)
    {
        $month = $request['due_date'];
        $userId = $request['userId'];

        return $totalAmountExpenses = $this->expenseRepository->getTotalAmountExpensesByMonth($userId, $month);
    }

    public function calculateBalanceByMonth($request)
    {
        $userId = $request['userId'];
        $month = $request['due_date'];

        $totalAmountIncomes = $this->incomeRepository->getTotalAmountIncomesByMonth($userId, $month);
        $totalAmountExpenses = $this->expenseRepository->getTotalAmountExpensesByMonth($userId, $month);

        return $balance = $totalAmountIncomes - $totalAmountExpenses;
    }

    public function calculatePercentageOfSavingsByMonth($request)
    {
        $userId = $request['userId'];
        $month = $request['due_date'];

        $totalAmountIncomes = $this->incomeRepository->getTotalAmountIncomesByMonth($userId, $month);
        $totalAmountExpenses = $this->expenseRepository->getTotalAmountExpensesByMonth($userId, $month);

        $percentOfSavings = 0;
        if($totalAmountIncomes != null and $totalAmountExpenses != null) {
            $percentOfSavings = (($totalAmountIncomes - $totalAmountExpenses) * 100) / $totalAmountIncomes;
        }
        return $percentOfSavings;
    }

    public function calculateBalanceGoalByMonth($request)
    {
        $userId = $request['userId'];
        $month = $request['due_date'];

        $totalAmountIncomes = $this->incomeRepository->getTotalAmountIncomesByMonth($userId,
        $month);
        return ($totalAmountIncomes * 30) / 100;
    }

    public function calculateAverageIncomesByYear($request)
    {
        $userId = $request['userId'];
        $year = $request['due_date'];

        return $averageIncomes = $this->incomeRepository->getAverageIncomes($userId, $year);
    }

    public function calculateAverageExpensesByYear($request)
    {
        $userId = $request['userId'];
        $year = $request['due_date'];

        return $averageExpenses = $this->expenseRepository->getAverageExpenses($userId, $year);
    }

    public function calculateAveragePercentOfSavingByYear($request)
    {
        $userId = $request['userId'];
        $year = $request['due_date'];

        return $averagePercent = $this->dashboardRepository->getAveragePercentOfSavingByYear($userId, $year);
    }

    public function getExpensesYearForChart($request)
    {
        $userId = $request['userId'];
        $year = $request['due_date'];

       return $expensesForChart = $this->expenseRepository->getExpensesYearForChart($userId, $year);
    }

    public function getIncomesYearForChart($request)
    {
        $userId = $request['userId'];
        $year = $request['due_date'];

       return $incomesForChart = $this->incomeRepository->getIncomesYearForChart($userId, $year);
    }

    public function getExpensesToBeDue($request)
    {
        $userId = $request['userId'];

        return $expensesToBeDue = $this->expenseRepository->getExpensesToBeDue($userId);
    }

    public function getIncomesToBeDue($request)
    {
        $userId = $request['userId'];

        return $incomesToBeDue = $this->incomeRepository->getIncomesToBeDue($userId);
    }
}
