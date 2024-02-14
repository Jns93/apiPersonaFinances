<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Income;
use App\Models\Subcategory;
use App\Models\User;

class IncomeTest extends TestCase
{
    public function test_get_incomes(): Void
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $user = User::factory()->create();

        $incomes = Income::factory(5)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'due_date' => '2024-02-20'
        ]);
        $incomes = Income::factory(5)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'due_date' => '2024-01-20'
        ]);

        $response = $this->getJson("/api/v1/incomes/byMonth/{$user->id}/2024-02-01");
        $response->assertJsonCount(5)
            ->assertStatus(200);
    }

    public function test_store_income()
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $user = User::factory()->create();
        $incomeData = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'description' => '',
            'name' => 'despesa',
            'amount' => 10,
            'fl_split' => 0,
            'due_date' => '2029-01-01'
        ];
        $response = $this->postJson('/api/v1/incomes', $incomeData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('incomes', [
            'name' => 'despesa',
        ]);
    }

    public function test_delete_income()
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $user = User::factory()->create();
        $incomeData = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'name' => 'despesa',
            'amount' => 10,
            'fl_split' => 0,
            'due_date' => '2029-01-01',
        ];
        $income = $this->postJson('/api/v1/expenses', $incomeData);

        $response = $this->delete("api/v1/incomes/{$income['id']}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('incomes', ['id' => $income['id']]);
    }

}
