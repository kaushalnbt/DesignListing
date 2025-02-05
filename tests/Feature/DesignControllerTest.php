<?php

namespace Tests\Feature;

use App\Models\Design;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DesignControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Design::factory()->count(3)->create();

        $response = $this->getJson('/api/designs');

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
        $data = ['name' => 'New Design'];

        $response = $this->postJson('/api/designs', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'message' => 'Design created successfully!',
                     'data' => ['name' => 'New Design']
                 ]);

        $this->assertDatabaseHas('designs', $data);
    }

    public function testShow()
    {
        $design = Design::factory()->create();

        $response = $this->getJson("/api/designs/{$design->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Design fetched successfully!',
                     'data' => ['id' => $design->id]
                 ]);
    }

    public function testUpdate()
    {
        $design = Design::factory()->create();
        $data = ['name' => 'Updated Design'];

        $response = $this->putJson("/api/designs/{$design->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Design updated successfully!',
                     'data' => ['name' => 'Updated Design']
                 ]);

        $this->assertDatabaseHas('designs', $data);
    }

    public function testDestroy()
    {
        $design = Design::factory()->create();

        $response = $this->deleteJson("/api/designs/{$design->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('designs', ['id' => $design->id]);
    }
}
