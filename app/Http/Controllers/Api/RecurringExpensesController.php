<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RecurringExpenses;
use App\Http\Requests\StoreRecurringExpensesRequest;
use App\Http\Requests\UpdateRecurringExpensesRequest;

class RecurringExpensesController extends Controller
{
    protected $service;

    public function __construct(RecurringExpenseService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRecurringExpensesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRecurringExpensesRequest $request)
    {
        $this->service->store($request);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RecurringExpenses  $recurringExpenses
     * @return \Illuminate\Http\Response
     */
    public function show(RecurringExpenses $recurringExpenses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RecurringExpenses  $recurringExpenses
     * @return \Illuminate\Http\Response
     */
    public function edit(RecurringExpenses $recurringExpenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRecurringExpensesRequest  $request
     * @param  \App\Models\RecurringExpenses  $recurringExpenses
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRecurringExpensesRequest $request, RecurringExpenses $recurringExpenses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RecurringExpenses  $recurringExpenses
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecurringExpenses $recurringExpenses)
    {
        dd($recurringExpenses);
    }
}
