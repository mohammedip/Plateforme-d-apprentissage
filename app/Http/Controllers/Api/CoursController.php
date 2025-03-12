<?php

namespace App\Http\Controllers\Api;

use App\Models\Cours;
use Illuminate\Http\Request;
use App\Http\Requests\CoursRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\CoursResource;
use App\Repositories\CoursRepositoryInterface;

/**
 * @OA\PathItem(path="/api/courses")
 */
class CoursController extends Controller
{
    protected $coursRepository;

    public function __construct(CoursRepositoryInterface $coursRepository)
    {
        $this->coursRepository = $coursRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/courses",
     *     tags={"courses"},
     *     summary="Get all courses",
     *     @OA\Response(response="200", description="A list of courses")
     * )
     */
    public function index()
    {
        $courses = $this->coursRepository->getAll();

        if ($courses->count() > 0) {
            return CoursResource::collection($courses);
        } else {
            return response()->json(['message' => 'No courses for the moment'], 200);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/courses",
     *     tags={"courses"},
     *     summary="Create a new course",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "category_id", "mentor_id"},
     *             @OA\Property(property="title", type="string", example="Mathematics 101"),
     *             @OA\Property(property="description", type="string", example="A basic introduction to mathematics."),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="mentor_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Course created successfully")
     * )
     */
    public function store(CoursRequest $request)
    {
        $cours = $this->coursRepository->create($request->validated());

        if ($request->has('tags_id')) {
            $tagIds = $request->input('tags_id');
            $cours->tags()->attach($tagIds);
        }

        return response()->json([
            'message' => 'Cours added successfully',
            'data' => new CoursResource($cours)
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/courses/{id}",
     *     tags={"courses"},
     *     summary="Get a course by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the course",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="category_id", type="integer"),
     *             @OA\Property(property="mentor_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Course not found")
     * )
     */
    public function show($id)
    {
        $cours = $this->coursRepository->findById($id);
        return new CoursResource($cours);
    }

    /**
     * @OA\Put(
     *     path="/api/courses/{id}",
     *     tags={"courses"},
     *     summary="Update a course",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the course to update",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "category_id", "mentor_id"},
     *             @OA\Property(property="title", type="string", example="Advanced Mathematics"),
     *             @OA\Property(property="description", type="string", example="An in-depth study of advanced mathematics."),
     *             @OA\Property(property="category_id", type="integer", example=9),
     *             @OA\Property(property="mentor_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course updated successfully",
     *         
     *     ),
     *     @OA\Response(response=404, description="Course not found")
     * )
     */
    public function update(CoursRequest $request, $id)
    {
        $cours = $this->coursRepository->findById($id);
        $updatedCours = $this->coursRepository->update($request->validated(), $cours);

        return response()->json([
            'message' => 'Cours updated successfully',
            'data' => new CoursResource($updatedCours)
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/courses/{id}",
     *     tags={"courses"},
     *     summary="Delete a course",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the course to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course deleted successfully"
     *     ),
     *     @OA\Response(response=404, description="Course not found")
     * )
     */
    public function destroy($id)
    {
        $cours = $this->coursRepository->findById($id);
        $this->coursRepository->delete($cours);

        return response()->json([
            'message' => 'Cours deleted successfully'
        ], 200);
    }
}
