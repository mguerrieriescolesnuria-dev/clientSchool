<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('ClientSchool Frontend');
        $response->assertSee('Iniciar sessió');
    }

    public function test_authenticated_user_can_open_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/app');

        $response->assertStatus(200);
        $response->assertSee('Panell del client SPA');
    }

    public function test_user_can_register_and_enter_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'Maria',
            'email' => 'maria@example.com',
            'password' => '1234',
        ]);

        $response->assertRedirect('/app');
        $this->assertDatabaseHas('users', ['email' => 'maria@example.com']);
    }

    public function test_proxy_returns_students_from_backend_api(): void
    {
        Http::fake([
            'http://127.0.0.1:8001/api/students' => Http::response([
                'status' => 200,
                'data' => [
                    ['id' => '1', 'name' => 'Ada Lovelace', 'email' => 'ada@example.com'],
                ],
            ], 200),
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/resources/students');

        $response->assertOk();
        $response->assertJsonPath('data.0.name', 'Ada Lovelace');
    }

    public function test_proxy_creates_teacher_in_backend_api(): void
    {
        Http::fake([
            'http://127.0.0.1:8001/api/teachers' => Http::response([
                'status' => 201,
                'data' => [
                    'id' => '99',
                    'name' => 'Grace Hopper',
                    'email' => 'grace@example.com',
                ],
                'message' => 'Teacher created successfully',
            ], 201),
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/resources/teachers', [
            'name' => 'Grace Hopper',
            'email' => 'grace@example.com',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('message', 'Teacher created successfully');
    }
}
