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
}
