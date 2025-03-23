<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test("can register a user", function () {
    $data = [
        'name' => 'simo',
        'email' => 'simo@example.com',
        'password' => 'password',
        'password_confirmation' => 'password'
    ];

    $response = $this->postJson("api/v2/register", $data);

    $response->assertStatus(201);
    $this->assertDatabaseHas('users', ['email' => 'simo@example.com']);
});

test("can login a user", function () {
  

    $response = $this->postJson("api/v2/login", [
        'email' => 'simo@example.com',
        'password' => 'password'
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['data' => ['user', 'token']]);
});

test("cannot login with incorrect credentials", function () {

    
    $response = $this->postJson("api/v2/login", [
        'email' => 'simo@example.com',
        'password' => 'wrongpassword'
    ]);

    $response->assertStatus(200);
    $response->assertJson(['message' => 'login information are incorrect']);
});

test("can logout a user", function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson("api/v2/logout");
    
    $response->assertStatus(200);
    $response->assertJson(['message' => 'Logout Successfully']);
});

test("can refresh token", function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson("api/v2/refresh");
    
    $response->assertStatus(200);
    $response->assertJsonStructure(['data' => ['user', 'token']]);
});
