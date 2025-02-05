<?php

namespace Tests\Feature;

use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        ProductCategory::factory()->count(3)->create();

        $response = $this->getJson('/api/product-categories');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         '*' => ['id', 'name', 'parent_id', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    public function testStore()
    {
        $data = ['name' => 'New Category'];

        $response = $this->postJson('/api/product-categories', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'message' => 'Product category created successfully!',
                     'data' => ['name' => 'New Category']
                 ]);

        $this->assertDatabaseHas('product_categories', $data);
    }

    public function testShow()
    {
        $category = ProductCategory::factory()->create();

        $response = $this->getJson("/api/product-categories/{$category->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Product category fetched successfully!',
                     'data' => ['id' => $category->id]
                 ]);
    }

    public function testUpdate()
    {
        $category = ProductCategory::factory()->create();
        $data = ['name' => 'Updated Category'];

        $response = $this->putJson("/api/product-categories/{$category->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Product category updated successfully!',
                     'data' => ['name' => 'Updated Category']
                 ]);

        $this->assertDatabaseHas('product_categories', $data);
    }

    public function testDestroy()
    {
        $category = ProductCategory::factory()->create();

        $response = $this->deleteJson("/api/product-categories/{$category->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('product_categories', ['id' => $category->id]);
    }
}
