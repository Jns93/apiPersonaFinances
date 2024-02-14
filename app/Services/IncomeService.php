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

    public function getIncomesByMonth($request)
    {
        $date = $request['due_date'];
        $userId = $request['user_id'];

        return $incomes = $this->incomeRepository->getIncomesByMonth($userId, $date);
    }


    public function store(array $request)
    {
       return $income = $this->incomeRepository->store($request);
    }

    public function delete($id)
    {
        return $this->incomeRepository->delete($id);
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
