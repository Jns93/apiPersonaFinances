<?php

namespace App\Repositories\Contracts;

interface RecurringExpenseRepositoryInterface
{
    public function index($userId);
    public function store(array $request);
    public function delete($request);
    public function update(array $request);
}
