<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can register a user', function () {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123ASD@#',
    ]);

    $response->assertStatus(201)
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('message', 'User registered successfully')
            ->etc());
});

it('can login a user', function () {
    $response = $this->postJson('/api/auth/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['message', 'data']);
});

it('can logout a user', function () {
    $response = $this->actingAs($this->user)->postJson('/api/auth/logout');

    $response->assertStatus(200)
        ->assertJson(['message' => 'User Logged Out Successfully']);
});
