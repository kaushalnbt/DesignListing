<?php

namespace Tests\Feature;

use App\Models\Structure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StructureControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Structure::factory()->count(3)->create();

        $response = $this->getJson('/api/structures');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         '*' => ['id', 'name', 'product_category_id', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    public function testStore()
    {
        $data = ['name' => 'New Structure'];

        $response = $this->postJson('/api/structures', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'message' => 'Structure created successfully!',
                     'data' => ['name' => 'New Structure']
                 ]);

        $this->assertDatabaseHas('structures', $data);
    }

    public function testShow()
    {
        $structure = Structure::factory()->create();

        $response = $this->getJson("/api/structures/{$structure->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Structure fetched successfully!',
                     'data' => ['id' => $structure->id]
                 ]);
    }

    public function testUpdate()
    {
        $structure = Structure::factory()->create();
        $data = ['name' => 'Updated Structure'];

        $response = $this->putJson("/api/structures/{$structure->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Structure updated successfully!',
                     'data' => ['name' => 'Updated Structure']
                 ]);

        $this->assertDatabaseHas('structures', $data);
    }

    public function testDestroy()
    {
        $structure = Structure::factory()->create();

        $response = $this->deleteJson("/api/structures/{$structure->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('structures', ['id' => $structure->id]);
    }
}
