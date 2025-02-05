<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         '*' => ['id', 'name', 'email', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    public function testStore()
    {
        $data = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'message' => 'User created successfully!',
                     'data' => ['name' => 'New User', 'email' => 'newuser@example.com']
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function testShow()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'User fetched successfully!',
                     'data' => ['id' => $user->id]
                 ]);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $data = ['name' => 'Updated User'];

        $response = $this->putJson("/api/users/{$user->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'User updated successfully!',
                     'data' => ['name' => 'Updated User']
                 ]);

        $this->assertDatabaseHas('users', $data);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
