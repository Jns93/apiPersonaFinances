<?php

namespace App\Repositories;

use App\Models\Income;
use App\Repositories\Contracts\IncomeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IncomeRepository implements IncomeRepositoryInterface
{
    protected $table;

    public function __construct()
    {
        $this->table = 'categories';
    }

    public function getIncomesByMonth($userId, $date)
    {
        $carbon = new Carbon($date);

        return Income::whereMonth('due_date', '=', $carbon->month)
                        ->whereYear('due_date', '=', $carbon->year)
                        ->where('user_Id', '=', $userId)
                        ->with('category')
                        ->get();
    }

    public function store(array $request)
    {
            $newIncome = new Income();
            $newIncome->user_id = 1;
            $newIncome->category_id = $request['category_id'];
            $newIncome->subcategory_id = $request['subcategory_id'];
            $newIncome->name = $request['name'];
            $newIncome->description = $request['description'];
            $amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $request['amount']);
            $newIncome->amount = floatval($amount);
            $newIncome->fl_pay = $request['fl_pay'];
            $newIncome->fl_fixed = $request['fl_fixed'];
            $newIncome->due_date = $request['due_date'];
            $newIncome->save();

            $income = Income::find($newIncome->id);

            return Income::find($newIncome->id)->with('category')->orderBy('id', 'desc')->get()->first();
    }

    public function delete(int $id)
    {
        $income = Income::find($id);
        $income->delete();

        return $income;
    }

    public function pay(array $ids)
    {
        foreach($ids as $item) {
            $incomeUpdated = Income::find($item['id']);
            $incomeUpdated->fl_pay = 1;
            $incomeUpdated->save();
        }
        return $incomeUpdated;
    }

    public function update(array $request)
    {
        $income = Income::find($request['id']);
        $income->category_id = $request['category_id'];
        $income->subcategory_id = $request['subcategory_id'];
        $income->name = $request['name'];
        $income->description = $request['description'];
        $amount = str_replace(['R$ ', '.', ','], ['', '', '.'], $request['amount']);
        $income->amount = floatval($amount);
        $income->fl_pay = $request['fl_pay'];
        $income->fl_fixed = $request['fl_fixed'];
        $income->due_date = $request['due_date'];
        $income->save();

        return Income::find($income->id)->with('category')->get()->first();
    }

    public function getTotalAmountIncomesByMonth($userId, $month)
    {
        $carbon = new Carbon($month);

        return Income::whereMonth('due_date', '=', $carbon->month)
                        ->whereYear('due_date', '=', $carbon->year)
                        ->where('user_Id', '=', $userId)
                        ->sum('amount');
    }

    public function getAverageIncomes($userId, $year)
    {
        $teste = DB::table('incomes')
                        ->select(
                            DB::raw('sum(amount) as amount'),
                            DB::raw('MONTH(due_date) month'))
                        ->whereYear('due_date', $year)
                        ->where('user_Id', '=', $userId)
                        ->groupBy('month')
                        ->get()
                        ->avg('amount');

        if(empty($teste)){
            return $teste = 0;
            } else {
                return $teste;
            }
    }

    public function getIncomesYearForChart($userId, $year)
    {
        return $incomesForChart = DB::table('incomes')
                                        ->select(
                                            DB::raw('sum(amount) as amount'),
                                            DB::raw("MONTH(due_date) as month")
                                        )
                                        ->whereYear('due_date', $year)
                                        ->groupBy(DB::raw("MONTH(due_date)"))
                                        ->get();
    }

    public function getIncomesToBeDue($userId)
    {
        $carbon = Carbon::now()->addDays(15);
        // dd($carbon);
        return Income::where('due_date', '<', $carbon)
                        ->where('fl_Pay', '=', false)
                        ->where('user_id', '=', $userId)
                        ->with('category')
                        ->get();
    }

    public function getIncomestByMonthChart($userId, $date)
    {
        $carbon = new Carbon($date);
        return DB::table('incomes')
                        ->select([
                            DB::raw("sum(amount) as amount"),
                            DB::raw("MONTH(due_date) as month")
                        ])
                        ->whereYear('due_date', '=', $carbon->year)
                        ->where('user_id', '=', $userId)
                        ->groupBy('month')
                        ->get();
    }
}
