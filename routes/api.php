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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('teste', function() {
//     $user = User::first();

//     //Criando token com sanctum:
//     $token = $user->createToken('name-token');
// });
//verifica se está autenticado e cria o token (fora do middleware pq ela valida se a senha esta certa e gera o token que passa pelo midle)


// Route::group([
//     'prefix' => 'v1',
//     'middleware' => ['auth:sanctum']
// ], function () {
//     Route::get('/auth/me', 'Api\Auth\AuthUserController@me'); //Rota para recuperar user (requer o token gerado na rota sanctum/token)
//     Route::post('/auth/logout', 'Api\Auth\AuthUserController@logout');
// });
Route::post('/v1/sanctum/token', 'Api\Auth\AuthUserController@auth');

Route::group([
    'prefix' => 'v1',
    'namespace' => 'Api',
    'middleware' => 'throttle:90,1',
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('/auth/me', 'Auth\AuthUserController@me'); //Rota para recuperar user (requer o token gerado na rota sanctum/token)
    Route::post('/auth/logout', 'Auth\AuthUserController@logout');

    Route::get('/categories', 'CategoryController@index');
    Route::post('/categories/store', 'CategoryController@store');
    Route::put('/categories/update', 'CategoryController@update');
    Route::delete('/categories/delete', 'CategoryController@delete');

    Route::get('/subcategories', 'SubcategoryController@getSubcategoriesByCategory');
    Route::get('/subcategories/all', 'SubcategoryController@getAllSubcategories');
    Route::post('/subcategories/store', 'SubcategoryController@store');
    Route::put('/subcategories/update', 'SubcategoryController@update');
    Route::delete('/subcategories/delete', 'SubcategoryController@delete');

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
    Route::get('/dashboard/expenses/expenses-year-for-chart', 'DashboardController@getExpensesYearForChart');
    Route::get('/dashboard/incomes/incomes-year-for-chart', 'DashboardController@getIncomesYearForChart');
    Route::get('/dashboard/expenses/expenses-to-be-due', 'DashboardController@getExpensesToBeDue');
    Route::get('/dashboard/incomes/incomes-to-be-due', 'DashboardController@getIncomesToBeDue');

    Route::post('/register-user', 'Auth\RegisterController@registerUser');
});

