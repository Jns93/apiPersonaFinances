<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\RecurringExpenseService;
use App\Repositories\RecurringExpenseRepository;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\RecurringExpense;

class RecurringExpensesTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_store_recurring_expense()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);

        $this->repository = new RecurringExpenseRepository();

        $request['user_id'] = $user->id;
        $request['category_id'] = $category->id;
        $request['subcategory_id'] = $subcategory->id;
        $request['name'] = 'teste';
        $request['description'] = 'teste ';
        $request['fl_fixed'] = 1;
        $request['fl_essential'] = 0;
        $request['expiration_day'] = 5;
        $request['amount'] = 10;

        $response = $this->repository->store($request);

        $this->assertEquals($response->status(), 201);
    }

    public function test_delete_recurring_expense()
    {
        $this->repository = new RecurringExpenseRepository();

        $user = User::factory()->create();
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $recurring = RecurringExpense::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'user_id' => $user->id,
        ]);


        $response = $this->repository->delete($recurring->id);

        $this->assertEquals($response->status(), 204);
    }

    public function test_update_recurring_expense()
    {
        $this->repository = new RecurringExpenseRepository();

        $user = User::factory()->create();
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $recurring = RecurringExpense::factory()->create([
            'category_id' => $category->id,
            'subcategory_id' => $subcategory->id,
            'user_id' => $user->id,
            'fl_essential' => 0,
            'fl_fixed' => 0,
        ]);
        $recurring = $recurring->toArray();
        $recurring['description'] = 'TESTE UPDATE';
        $response = $this->repository->update($recurring);

        $this->assertEquals($response->status(), 200);
    }
}
