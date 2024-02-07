<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RecurringExpense;
use App\Models\RecurringHistory;
use App\Http\Requests\StoreRecurringExpensesRequest;
use App\Http\Requests\UpdateRecurringExpensesRequest;
use App\Services\RecurringExpenseService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RecurringExpenseController extends Controller
{
    protected $service;

    public function __construct(RecurringExpenseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $expenses = $this->service->index($request);
    }

    public function store(StoreRecurringExpensesRequest $request)
    {
        return $this->service->store($request);
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
    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function execute()
    {
        return $this->service->execute();
    }
}
