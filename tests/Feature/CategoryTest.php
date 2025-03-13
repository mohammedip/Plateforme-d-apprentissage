<?php

use App\Models\Category;

test("can list categories", function () {

    $response = $this->get("api/v1/categories");
    $response->assertStatus(200);
    $response->assertJsonStructure([
        "data" => [
            "*" => [
                'id', 'name', 'category_id'
            ],
        ],
    ]);
});

test("can create a category", function () {
    $data = ['name' => 'Electronics', 'category_id' => null];

    $response = $this->postJson("api/v1/categories", $data);

    $response->assertStatus(201);
    $this->assertDatabaseHas('categories', ['name' => 'Electronics']);
});

test("can show a category", function () {
    $category = Category::factory()->create();

    $response = $this->get("api/v1/categories/{$category->id}");

    $response->assertStatus(200);
    $response->assertJsonFragment([ 'id' => $category->id, 'name' => $category->name ]);
});

test("can update a category", function () {
    $category = Category::factory()->create();
    $updatedData = ['name' => 'Updated Category', 'category_id' => null];

    $response = $this->putJson("api/v1/categories/{$category->id}", $updatedData);

    $response->assertStatus(201);
    $this->assertDatabaseHas('categories', $updatedData);
});

test("can delete a category", function () {
    $category = Category::factory()->create();

    $response = $this->delete("api/v1/categories/{$category->id}");

    $response->assertStatus(200);
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});