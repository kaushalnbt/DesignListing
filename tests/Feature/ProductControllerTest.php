<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         '*' => ['id', 'name', 'category_id', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    public function testStore()
    {
        $category = ProductCategory::factory()->create();
        $data = [
            'name' => 'New Product',
            'category_id' => $category->id
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'message' => 'Product created successfully!',
                     'data' => ['name' => 'New Product']
                 ]);

        $this->assertDatabaseHas('products', $data);
    }

    public function testShow()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Product fetched successfully!',
                     'data' => ['id' => $product->id]
                 ]);
    }

    public function testUpdate()
    {
        $product = Product::factory()->create();
        $data = ['name' => 'Updated Product'];

        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Product updated successfully!',
                     'data' => ['name' => 'Updated Product']
                 ]);

        $this->assertDatabaseHas('products', $data);
    }

    public function testDestroy()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
