<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\Http\Resources\DashboardResource;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function getBalanceByMonth(Request $request)
    {
        return $balance = $this->dashboardService->calculateBalanceByMonth($request);
    }

    public function getTotalAmountIncomesByMonth(Request $request)
    {
        return $totalAmountIncomes = $this->dashboardService->getTotalAmountIncomesByMonth($request);
    }

    public function getTotalAmountExpensesByMonth(Request $request)
    {
        return $totalAmountExpenses = $this->dashboardService->getTotalAmountExpensesByMonth($request);
    }

    public function getPercentageOfSavingsByMonth(Request $request)
    {
        return $percentOfSavings = $this->dashboardService->calculatePercentageOfSavingsByMonth($request);
    }

    public function getBalanceGoalByMonth(Request $request)
    {
        return $balanceGoal = $this->dashboardService->calculateBalanceGoalByMonth($request);
    }

    public function getAverageIncomesByYear(Request $request)
    {
        return $averageIncomes = $this->dashboardService->calculateAverageIncomesByYear($request);
    }

    public function getAverageExpensesByYear(Request $request)
    {
        return $averageExpenses = $this->dashboardService->calculateAverageExpensesByYear($request);
    }

    public function getAveragePercentOfSavingByYear(Request $request)
    {
        return $averagePercent = $this->dashboardService->calculateAveragePercentOfSavingByYear($request);
    }

    public function getExpensesYearForChart(Request $request)
    {
        return $expensesForChart = $this->dashboardService->getExpensesYearForChart($request);
    }

    public function getIncomesYearForChart(Request $request)
    {
        return $incomesForChart = $this->dashboardService->getIncomesYearForChart($request);
    }

    public function getExpensesToBeDue(Request $request)
    {
        return $expensesToBeDue = $this->dashboardService->getExpensesToBeDue($request);
    }

    public function getIncomesToBeDue(Request $request)
    {
        return $incomesToBeDue = $this->dashboardService->getIncomesToBeDue($request);
    }
}
