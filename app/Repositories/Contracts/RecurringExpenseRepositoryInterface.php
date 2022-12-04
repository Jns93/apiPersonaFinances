<?php

namespace App\Repositories\Contracts;

interface RecurringExpenseRepositoryInterface
{
    public function index($userId);
    public function store($request);
    public function delete($request);
    public function update(array $request);
    public function execute();
    public function needToRun();
}
