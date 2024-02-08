<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Subcategory;


class SubCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_sub_categories(): void
    {
        $category = Category::factory()->create([
            'id' => 1,
        ]);
        $subcategory = Subcategory::factory()->create([
            'category_id' => 1,
        ]);
        $response = $this->getJson('/api/v1/subcategories/by-category/1');

        $response->assertStatus(200);
        $response->assertJsonCount(1)
                ->assertJsonFragment(['name' => $subcategory->name]);;
    }
    public function test_get_all_subcategories(): Void
    {
        Category::factory()->create([
            'id' => 1,
        ]);
        $subcategory = Subcategory::factory(2)->create([
            'category_id' => 1,
        ]);
        $response = $this->get('/api/v1/subcategories/all');

        $response->assertStatus(200)
                ->assertJsonCount(2, 'data');
    }

    public function test_delete_subcategory()
    {
        Category::factory()->create([
            'id' => 1,
        ]);
        $subcategory = Subcategory::factory(1)->create([
            'category_id' => 1,
        ]);
        $subcategories = $this->get('/api/v1/subcategories/all');

        $response = $this->delete('/api/v1/subcategories/' . $subcategories['data'][0]['id']);
        $response->assertStatus(204);
        $subcategories = $this->get('/api/v1/subcategories/all');
        $subcategories->assertJsonCount(0, 'data');
    }
    public function test_store_subcategory()
    {
        Category::factory()->create([
            'id' => 1,
        ]);
        $response = $this->post('/api/v1/subcategories', [
            'category_id' => 1,
            'name' => 'Tesadfdas'
        ]);
        $response->assertJsonCount(1)
                ->assertJsonFragment([ 'name' => 'Tesadfdas'])
                ->assertStatus(201);
    }
    public function test_update_subcategory(): void
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        $newName = 'Nova Subcategoria';
        $response = $this->putJson('/api/v1/subcategories/', [
            'id' => $subcategory->id,
            'name' => $newName
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('subcategories', [
            'id' => $subcategory->id,
            'name' => $newName,
        ]);
    }
}
