<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    private $url = 'api/v1/categories/';
    public function test_create_category()
    {
        $response = $this->postJson($this->url . 'store', [
            'name' => 'Nova Categoria',
        ]);

        $response->assertStatus(201); // Verifica se a resposta é um sucesso
        $this->assertDatabaseHas('categories', ['name' => 'Nova Categoria']);
    }

    public function test_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->putJson($this->url . 'update', [
            'name' => 'category updated',
            'id' => $category['id']
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'category updated']);
    }

    public function test_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson($this->url . "delete", [
            'id' => $category['id']
        ]);

        $response->assertStatus(204);
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }
}
