<?php

namespace App\Services;

use App\Repositories\Contracts\IncomeRepositoryInterface;

class IncomeService
{
    protected $incomeRepository;

    public function __construct(IncomeRepositoryInterface $incomeRepository)
    {
        $this->incomeRepository = $incomeRepository;
    }

    // public function getIncomes()
    // {
    //     return $incomes = $this->incomeRepository->getIncomes();
    // }

    public function getIncomesByMonth($request)
    {
        $date = $request['due_date'];

        return $incomes = $this->incomeRepository->getIncomesByMonth($date);
    }


    public function store(array $request)
    {
       return $income = $this->incomeRepository->store($request);
    }

    // public function storeInstallment(array $request)
    // {
    //     return $income = $this->incomeRepository->storeInstallment($request);
    // }

    public function delete($id)
    {
        $income = $this->incomeRepository->delete($id);

        return $income;
    }

    public function pay(array $ids)
    {
        return $this->incomeRepository->pay($ids);
    }

    public function update(array $request)
    {
        return $this->incomeRepository->update($request);
    }
}
