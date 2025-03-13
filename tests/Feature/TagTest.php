<?php

use App\Models\Tag;

test(" can list tags",function(){

    $responce = $this->get("api/v1/tags");
    $responce->assertStatus(200);
    $responce->assertJsonStructure([
        "data" => [
            "*" => [
                'name',
            ],
        ],
    ]);
});

test("can create a tag", function () {
    $data = ['tags' => 'Technology,Science'];

    $response = $this->postJson("api/v1/tags", $data);

    $response->assertStatus(201);
    $this->assertDatabaseHas('tags', ['name' => 'Technology']);
    $this->assertDatabaseHas('tags', ['name' => 'Science']);
});

test("can show a tag", function () {
    $tag = Tag::factory()->create();

    $response = $this->get("api/v1/tags/{$tag->id}");

    $response->assertStatus(200);
    $response->assertJsonFragment([ 'id' => $tag->id, 'name' => $tag->name ]);
});

test("can update a tag", function () {
    $tag = Tag::factory()->create();
    $updatedData = ['name' => 'Updated Name'];

    $response = $this->putJson("api/v1/tags/{$tag->id}", $updatedData);

    $response->assertStatus(201);
    $this->assertDatabaseHas('tags', $updatedData);
});

test("can delete a tag", function () {
    $tag = Tag::factory()->create();

    $response = $this->delete("api/v1/tags/{$tag->id}");

    $response->assertStatus(200);
    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
});


