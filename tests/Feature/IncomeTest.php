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


        $request = [
            'user_id' => $user->id,
            'due_date' => '2024-02-01'
        ];
        $response = $this->getJson("/api/v1/incomes/byMonth/{$user->id}/2024-02-01");
        $response->assertJsonCount(5)
            ->assertStatus(200);
    }

}
