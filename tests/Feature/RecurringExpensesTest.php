<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\RecurringExpense;
use App\Models\Subcategory;
use App\Repositories\ExpenseRepository;
use App\Repositories\IncomeRepository;
use App\Repositories\RecurringExpenseRepository;
use App\Services\RecurringExpenseService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class RecurringExpensesTest extends TestCase
{
    use RefreshDatabase;
    protected $recurringExpenseRepository;

    public function test_get_recurring_exepnses()
    {
        $response = $this->getJson('/api/v1/recurringExpenses');

        $response->assertStatus(200);
    }

    public function test_store_recurring_expense_success()
    {
        $category = Category::factory()->create([
            'id' => 1,
            'name' => 'asdsadf'
        ]);
        $subcategory = Subcategory::factory()->create([
            'id' => 1,
            'category_id' => 1,
            'name' => 'asdsadf'
        ]);
        $user = User::factory()->create();
        $requestData = [
            'category_id' => 1,
            'subcategory_id' => 1,
            'name' => 'Teste',
            'amount' => 10,
            'expiration_day' => 5,
            'user_id' => $user->id,
            'fl_essential' => 0,
            'description' => 'teste',
            'fl_fixed' => 1,
            'type' => 1
        ];

        $response = $this->postJson('/api/v1/recurringExpenses/store', $requestData);

        $response->assertStatus(201);
    }
}
