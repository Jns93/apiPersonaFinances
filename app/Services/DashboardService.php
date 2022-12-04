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

        $totalAmountIncomes = $this->incomeRepository->getTotalAmountIncomesByMonth($userId, $month);
        return $totalAmountIncomes;
    }

    public function getTotalAmountExpensesByMonth($request)
    {
        $month = $request['due_date'];
        $userId = $request['userId'];

        $totalAmountExpenses = $this->expenseRepository->getTotalAmountExpensesByMonth($userId, $month);
        return $totalAmountExpenses;
    }

    public function calculateBalanceByMonth($request)
    {
        $userId = $request['userId'];
        $month = $request['due_date'];

        $totalAmountIncomes = $this->incomeRepository->getTotalAmountIncomesByMonth($userId, $month);
        $totalAmountExpenses = $this->expenseRepository->getTotalAmountExpensesByMonth($userId, $month);

        $balance = $totalAmountIncomes - $totalAmountExpenses;
        return $balance;
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
        return number_format($percentOfSavings, 2, ',', '.');
    }

    public function calculateBalanceGoalByMonth($request)
    {
        $userId = $request['userId'];
        $month = $request['due_date'];

        $totalAmountIncomes = $this->incomeRepository->getTotalAmountIncomesByMonth($userId,
        $month);
        $totalAmountIncomes = ($totalAmountIncomes * 30) / 100;
        return $totalAmountIncomes;
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

    public function getExpensestByCategoryChart($request)
    {
        return $this->expenseRepository->getExpensestByCategoryChart($request['userId'], $request['due_date']);
    }

    public function getExpensestByMonthChart($request)
    {
        return $this->expenseRepository->getExpensestByMonthChart($request['userId'], $request['due_date']);
    }

    public function getIncomestByMonthChart($request)
    {
        return $this->incomeRepository->getIncomestByMonthChart($request['userId'], $request['due_date']);
    }
}
