<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Subcategory;
use App\Models\User;
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
        $response = $this->getJson('/api/v1/expenses');
        $response->assertJsonCount(2, 'data');
    }
}
