<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'prefix' => 'v1',
    'namespace' => 'Api'
], function () {
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

});

