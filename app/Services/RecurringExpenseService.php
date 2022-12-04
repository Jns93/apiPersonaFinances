<?php

namespace App\Services;

use App\Repositories\Contracts\RecurringExpenseRepositoryInterface;

class RecurringExpenseService
{
    protected $repository;

    public function __construct(RecurringExpenseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($request)
    {
        $userId = $request['userId'];

        return $expenses = $this->repository->index($userId);
    }

    public function store($request)
    {
        return $this->repository->store($request);
    }

    public function delete($request)
    {
        $expense = $this->repository->delete($request['id']);

        return $expense;
    }

    public function update(array $request)
    {
        return $this->repository->update($request);
    }

    public function execute()
    {
        try{
            if($this->repository->needToRun())
                return $this->repository->execute();

            return response('Sucesso', 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
}
