<?php

namespace Tests\Feature;

use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SizeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Size::factory()->count(3)->create();

        $response = $this->getJson('/api/sizes');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         '*' => ['id', 'name', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    public function testStore()
    {
        $data = ['name' => 'New Size'];

        $response = $this->postJson('/api/sizes', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'message' => 'Size created successfully!',
                     'data' => ['name' => 'New Size']
                 ]);

        $this->assertDatabaseHas('sizes', $data);
    }

    public function testShow()
    {
        $size = Size::factory()->create();

        $response = $this->getJson("/api/sizes/{$size->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Size fetched successfully!',
                     'data' => ['id' => $size->id]
                 ]);
    }

    public function testUpdate()
    {
        $size = Size::factory()->create();
        $data = ['name' => 'Updated Size'];

        $response = $this->putJson("/api/sizes/{$size->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Size updated successfully!',
                     'data' => ['name' => 'Updated Size']
                 ]);

        $this->assertDatabaseHas('sizes', $data);
    }

    public function testDestroy()
    {
        $size = Size::factory()->create();

        $response = $this->deleteJson("/api/sizes/{$size->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('sizes', ['id' => $size->id]);
    }
}
