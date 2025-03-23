<?php

namespace Tests\Feature;

use App\Models\Cours;



    test("can list courses", function () {
        Cours::factory()->count(3)->create();

        $response = $this->get("api/v1/courses");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "*" => [
                    'title',
                    'description',
                    'category_id',
                    'mentor_id',
                ],
            ],
        ]);
    });

    test("can create a course", function () {
        $data = [
            'title' => 'Mathematics 101',
            'description' => 'A basic introduction to mathematics.',
            'category_id' => 9,
            'mentor_id' => 2,
        ];

        $response = $this->postJson("api/v1/courses", $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('courses', ['title' => 'Mathematics 101']);
    });

    test("can show a course", function () {
        $course = Cours::factory()->create();

        $response = $this->get("api/v1/courses/{$course->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
        ]);
    });

    test("can update a course", function () {
        $course = Cours::factory()->create();
        $updatedData = [
            'title' => 'Advanced Mathematics',
            'description' => 'An in-depth study of advanced mathematics.',
            'category_id' => 9,
            'mentor_id' => 2,
        ];

        $response = $this->putJson("api/v1/courses/{$course->id}", $updatedData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('courses', $updatedData);
    });

    test("can delete a course", function () {
        $course = Cours::factory()->create();

        $response = $this->delete("api/v1/courses/{$course->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    });

