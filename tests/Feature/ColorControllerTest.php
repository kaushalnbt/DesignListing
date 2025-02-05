<?php

namespace Tests\Feature;

use App\Models\Color;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ColorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Color::factory()->count(3)->create();

        $response = $this->getJson('/api/colors');

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
        $data = ['name' => 'New Color'];

        $response = $this->postJson('/api/colors', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'message' => 'Color created successfully!',
                     'data' => ['name' => 'New Color']
                 ]);

        $this->assertDatabaseHas('colors', $data);
    }

    public function testShow()
    {
        $color = Color::factory()->create();

        $response = $this->getJson("/api/colors/{$color->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Color fetched successfully!',
                     'data' => ['id' => $color->id]
                 ]);
    }

    public function testUpdate()
    {
        $color = Color::factory()->create();
        $data = ['name' => 'Updated Color'];

        $response = $this->putJson("/api/colors/{$color->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Color updated successfully!',
                     'data' => ['name' => 'Updated Color']
                 ]);

        $this->assertDatabaseHas('colors', $data);
    }

    public function testDestroy()
    {
        $color = Color::factory()->create();

        $response = $this->deleteJson("/api/colors/{$color->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('colors', ['id' => $color->id]);
    }
}
