<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

test("can create a video", function () {
    Storage::fake('public');


    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $data = [
        'title' => 'Electronics',
        'cours_id' => 60,
        'description' => 'description video 45',
        'video' => UploadedFile::fake()->create('video.mp4', 1000, 'video/mp4'),
    ];

    $response = $this->postJson("api/v2/videos", $data);

    $response->assertStatus(201);


    $videoPath = Video::first()->url;
    $this->assertTrue(Storage::disk('public')->exists($videoPath));

    $this->assertDatabaseHas('videos', ['title' => 'Electronics']);
});

test("can update a video", function () {
    Storage::fake('public');


    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $video = Video::factory()->create(['url' => 'videos/original.mp4']);

    $updatedData = [
        'title' => 'Advanced Electronics',
        'description' => 'Updated video description',
        'url' => UploadedFile::fake()->create('new_video.mp4', 2000, 'video/mp4'),
    ];

    $response = $this->putJson("api/v2/videos/{$video->id}", $updatedData);

    $response->assertStatus(200);


    $updatedVideoPath = Video::find($video->id)->url;
    $this->assertTrue(Storage::disk('public')->exists($updatedVideoPath));
    $this->assertFalse(Storage::disk('public')->exists('videos/original.mp4'));

    $this->assertDatabaseHas('videos', ['title' => 'Advanced Electronics']);
});

test("can delete a video", function () {
    Storage::fake('public');


    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $video = Video::factory()->create(['url' => 'videos/video_to_delete.mp4']);
    Storage::disk('public')->put($video->url, 'fake content');

    $response = $this->delete("api/v2/videos/{$video->id}");

    $response->assertStatus(200);


    $this->assertFalse(Storage::disk('public')->exists($video->url));
    $this->assertDatabaseMissing('videos', ['id' => $video->id]);
});
