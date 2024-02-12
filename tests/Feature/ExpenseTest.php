<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    public function test_get_expenses(): Void
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(
            [
                'category_id' => $category->id
            ]
        );
        $user = User::factory()->create();

        $expenses = Expense::factory(2)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id
        ]);
        $response = $this->getJson('/api/v1/expenses/by-user-id/' . $user->id);
        $response->assertJsonCount(2)
            ->assertStatus(200);
    }

    public function test_store_expense()
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(
            [
                'category_id' => $category->id
            ]
        );
        $user = User::factory()->create();
        $expensesData = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'despesa',
            'amount' => 10,
            'fl_split' => 0,
            'due_date' => '2029-01-01'
        ];
        $response = $this->postJson('/api/v1/expenses', $expensesData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('expenses', [
            'name' => 'despesa',
        ]);
        $responseData = $response->json();
        $this->assertEquals(10, $responseData['installments'][0]['amount']);
        $this->assertCount(1, $responseData['installments']);
    }
    public function test_store_splited_expense()
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(
            [
                'category_id' => $category->id
            ]
        );
        $user = User::factory()->create([
            'id' => 1
        ]);
        $user2 = User::factory()->create(
            [
                'id' => 2
            ]
        );
        $expensesData = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'despesa',
            'amount' => 10,
            'fl_split' => 1,
            'due_date' => '2029-01-01'
        ];
        $response = $this->postJson('/api/v1/expenses', $expensesData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('expenses', [
            'name' => 'despesa',
        ]);
        $responseData = $response->json();
        $this->assertEquals(5, $responseData['installments'][0]['amount']);
        $this->assertDatabaseCount('installments', 2);
    }
    public function test_store_expense_with_installment()
    {
        $category = Category::factory()->create();

        $subcategory = Subcategory::factory()->create(
            [
                'category_id' => $category->id
            ]
        );

        $user = User::factory()->create();

        $expensesData = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'despesa',
            'amount' => 10,
            'fl_split' => 0,
            'due_date' => '2029-01-01',
            'installments' => 3
        ];

        $response = $this->postJson('/api/v1/expenses', $expensesData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('expenses', [
            'name' => 'despesa',
        ]);

        $responseData = $response->json();
        $this->assertEquals(10, $responseData['installments'][0]['amount']);
        $this->assertCount(3, $responseData['installments']);
        $this->assertEquals(2, $responseData['installments'][1]['number_installment']);
        $this->assertEquals(3, $responseData['installments'][1]['amount_installments']);
    }

    public function test_store_splited_expense_with_installments()
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(
            [
                'category_id' => $category->id
            ]
        );
        $user = User::factory()->create([
            'id' => 1
        ]);
        $user2 = User::factory()->create(
            [
                'id' => 2
            ]
        );

        $expensesData = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'despesa',
            'amount' => 10,
            'fl_split' => 1,
            'due_date' => '2029-01-01',
            'installments' => 3
        ];
        $response = $this->postJson('/api/v1/expenses', $expensesData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('expenses', [
            'name' => 'despesa',
        ]);

        $responseData = $response->json();

        $this->assertEquals(5, $responseData['installments'][0]['amount']);
        $this->assertDatabaseCount('installments', 6);
    }

    public function test_delete_expense()
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $user = User::factory()->create();
        $expensesData = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'despesa',
            'amount' => 10,
            'fl_split' => 0,
            'due_date' => '2029-01-01',
        ];
        $expense = $this->postJson('/api/v1/expenses', $expensesData);

        $response = $this->delete("api/v1/expenses/{$expense['id']}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('expenses', ['id' => $expense['id']]);
    }

    public function test_pay_expense()
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $user = User::factory()->create();
        $expensesData = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'despesa',
            'amount' => 10,
            'fl_split' => 0,
            'due_date' => '2029-01-01',
        ];
        $expense = $this->postJson('/api/v1/expenses', $expensesData);

        $response = $this->put('/api/v1/expenses/pay', ['id' => $expense['id']]);
        $response->assertStatus(200);
        $this->assertEquals(1, $response[0]['installments'][0]['fl_pay']);
    }

    public function test_update_expense()
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $user = User::factory()->create();
        $expense = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'despesa inicial',
            'amount' => 10,
            'fl_split' => 0,
            'due_date' => '2029-01-01',
        ];
        $expense = $this->postJson('/api/v1/expenses', $expense);

        $request = [
            'id' => $expense['id'],
            'name' => 'despesa atualizada',
            'installment' => $expense['installments'][0]
        ];
        $request['installment']['amount'] = 25;

        $response = $this->put("/api/v1/expenses/", $request);

        $response->assertStatus(200);
        $this->assertDatabaseHas('expenses', [
            'id' => $expense['id'],
            'name' => $request['name'],
        ]);
        $this->assertDatabaseHas('installments', [
            'expense_id' => $expense['id'],
            'amount' => $request['installment']['amount']
        ]);
    }
}
