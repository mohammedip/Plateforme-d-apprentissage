<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use Illuminate\Auth\Events\Validated;

/**
 * @OA\PathItem(path="127.0.0.1:8000/api/v2/videos")
 */  
class VideoController extends Controller
{
        /**
     * @OA\Post(
     *     path="/api/v1/videos",
     * tags={"videos"},
     *     summary="Create a new Video",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="title", type="string", example="Electronics"),
     *             @OA\Property(property="cours_id", type="integer", example=60),
     *             @OA\Property(property="description", type="string", example="description video 45"),
     *             @OA\Property(property="url", type="integer", example="sdfghjklm")
     *         
     *         )
     *     ),
     *     @OA\Response(response=201, description="Category created successfully")
     * )
     */

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
 * @OA\Put(
 *     path="/api/v1/videos/{id}",
 *     tags={"videos"},
 *     summary="Updates a video",
 *     @OA\Parameter(
 *         description="ID of the video to update",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=false, 
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="Electronics"),
 *             @OA\Property(property="description", type="string", example="description video 45")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Video updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Video not found"
 *     )
 * )
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
     * @OA\Delete(
     *     path="/api/v1/videos/{id}",
     * tags={"videos"},
     *     summary="Delete a videos",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the video to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Video deleted successfully"
     *     )
     * )
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
