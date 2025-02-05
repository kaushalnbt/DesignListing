<?php

namespace Tests\Feature;

use App\Models\Finish;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinishControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Finish::factory()->count(3)->create();

        $response = $this->getJson('/api/finishes');

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
        $data = ['name' => 'New Finish'];

        $response = $this->postJson('/api/finishes', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'message' => 'Finish created successfully!',
                     'data' => ['name' => 'New Finish']
                 ]);

        $this->assertDatabaseHas('finishes', $data);
    }

    public function testShow()
    {
        $finish = Finish::factory()->create();

        $response = $this->getJson("/api/finishes/{$finish->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Finish fetched successfully!',
                     'data' => ['id' => $finish->id]
                 ]);
    }

    public function testUpdate()
    {
        $finish = Finish::factory()->create();
        $data = ['name' => 'Updated Finish'];

        $response = $this->putJson("/api/finishes/{$finish->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Finish updated successfully!',
                     'data' => ['name' => 'Updated Finish']
                 ]);

        $this->assertDatabaseHas('finishes', $data);
    }

    public function testDestroy()
    {
        $finish = Finish::factory()->create();

        $response = $this->deleteJson("/api/finishes/{$finish->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('finishes', ['id' => $finish->id]);
    }
}
