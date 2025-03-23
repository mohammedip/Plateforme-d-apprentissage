<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use Illuminate\Auth\Events\Validated;

class VideoController extends Controller
{
    public function store(StoreVideoRequest $request)
    {

        $data = $request->validated();

        if ($request->hasFile('video')) {
            $filePath = $request->file('video')->store('videos', 'public');
            $data['url'] = $filePath; 


            $video = Video::create($data);

            return response()->json([
                'message' => 'Video uploaded successfully',
                'video'   => $video,
            ], 201);
        }

        return response()->json(['message' => 'No video uploaded'], 400);
    }

    /**
     * Update an existing video.
     */
    public function update(UpdateVideoRequest $request, Video $video)
    {

        $data = $request->validated(); 

        if ($request->hasFile('video')) {

            if ($video->url) {
                Storage::disk('public')->delete($video->url);
            }


            $filePath = $request->file('video')->store('videos', 'public');
            $data['url'] = $filePath;


            $video->update($data);
        } else {

            $video->update($request->only(['title', 'description']));
        }

        return response()->json([
            'message' => 'Video updated successfully',
            'video'   => $video,
        ]);
    }

    /**
     * Delete a video.
     */
    public function destroy(Video $video)
    {
        if ($video->url) {
            Storage::disk('public')->delete($video->url);
        }

        $video->delete();

        return response()->json(['message' => 'Video deleted successfully']);
    }
}
