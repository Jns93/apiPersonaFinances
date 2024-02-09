<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/v1/sanctum/token', 'Api\Auth\AuthUserController@auth');

Route::group([
    'prefix' => 'v1',
    'namespace' => 'Api',
    'middleware' => 'throttle:90,1',
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('/auth/me', 'Auth\AuthUserController@me'); //Rota para recuperar user (requer o token gerado na rota sanctum/token)
    Route::post('/auth/logout', 'Auth\AuthUserController@logout');

    Route::get('/recurringExpenses', 'RecurringExpenseController@index');
    Route::get('/recurringExpenses/execute', 'RecurringExpenseController@execute');
    Route::post('/recurringExpenses/store', 'RecurringExpenseController@store');
    Route::put('/recurringExpenses/update', 'RecurringExpenseController@update');
    Route::delete('/recurringExpenses/{id}', 'RecurringExpenseController@destroy');

    Route::get('/categories', 'CategoryController@index');
    Route::post('/categories/store', 'CategoryController@store');
    Route::put('/categories/update', 'CategoryController@update');
    Route::delete('/categories/delete', 'CategoryController@delete');

    Route::get('/subcategories/by-category/{categoryId}', 'SubcategoryController@getSubcategoriesByCategory');
    Route::get('/subcategories/all', 'SubcategoryController@getAllSubcategories');
    Route::post('/subcategories', 'SubcategoryController@store');
    Route::put('/subcategories', 'SubcategoryController@update');
    Route::delete('/subcategories/{id}', 'SubcategoryController@delete');

    Route::get('/expenses', 'ExpenseController@index');
    Route::get('/expenses/byMonth', 'ExpenseController@getExpensesByMonth');
    Route::post('/expenses/store', 'ExpenseController@store');
    Route::delete('/expenses/delete', 'ExpenseController@delete');
    Route::put('/expenses/pay', 'ExpenseController@pay');
    Route::put('/expenses/update', 'ExpenseController@update');

    Route::get('/incomes', 'IncomeController@index');
    Route::get('/incomes/byMonth', 'IncomeController@getIncomesByMonth');
    Route::post('/incomes/store', 'IncomeController@store');
    Route::delete('/incomes/delete', 'IncomeController@delete');
    Route::put('/incomes/pay', 'IncomeController@pay');
    Route::put('/incomes/update', 'IncomeController@update');

    Route::get('/dashboard/indicators-month', 'DashboardController@getIndicatorsByMonth');
    Route::get('/dashboard/balance', 'DashboardController@getBalanceByMonth');
    Route::get('/dashboard/incomes/amount-total', 'DashboardController@getTotalAmountIncomesByMonth');
    Route::get('/dashboard/expenses/amount-total', 'DashboardController@getTotalAmountExpensesByMonth');
    Route::get('/dashboard/percent-of-savings', 'DashboardController@getPercentageOfSavingsByMonth');
    Route::get('/dashboard/balance-goal-by-month', 'DashboardController@getBalanceGoalByMonth');
    Route::get('/dashboard/incomes/average-incomes-by-Year', 'DashboardController@getAverageIncomesByYear');
    Route::get('/dashboard/expenses/average-expenses-by-Year', 'DashboardController@getAverageExpensesByYear');
    Route::get('/dashboard/average-percent-of-saving-by-Year', 'DashboardController@getAveragePercentOfSavingByYear');
    Route::get('/dashboard/charts/expenses/by-category', 'DashboardController@getExpensestByCategoryChart');
    Route::get('/dashboard/charts/expenses/by-month', 'DashboardController@getExpensestByMonthChart');
    Route::get('/dashboard/charts/incomes/by-month', 'DashboardController@getIncomestByMonthChart');

    Route::get('/dashboard/expenses/expenses-to-be-due', 'DashboardController@getExpensesToBeDue');
    Route::get('/dashboard/incomes/incomes-to-be-due', 'DashboardController@getIncomesToBeDue');

    Route::post('/register-user', 'Auth\RegisterController@registerUser');
});

