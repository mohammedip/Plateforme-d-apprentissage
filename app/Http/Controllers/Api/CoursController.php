<?php

namespace App\Http\Controllers\Api;

use App\Models\Cours;
use App\Models\Enrollement;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Requests\CoursRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CoursResource;
use App\Http\Resources\EnrollementResource;
use App\Repositories\CoursRepositoryInterface;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

/**
 * @OA\PathItem(path="/api/courses")
 */
class CoursController extends Controller implements HasMiddleware
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

    public static function middleware(){

        return [
            new Middleware('auth:sanctum', except: ['index' , 'show'])
        ];
    }

    public function index()
    {
        $courses = $this->coursRepository->getAll();

        if ($courses->count() > 0) {
            return ApiResponseClass::sendResponse(CoursResource::collection($courses),'',200);
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
        try{
            $cours = $this->coursRepository->create(array_merge($request->validated(),['mentor_id' => Auth::user()->id]));
                if ($request->has('tags_id')) {
                $tagIds = $request->input('tags_id');
                $cours->tags()->attach($tagIds);
            }
            return ApiResponseClass::sendResponse(new CoursResource($cours),'Cours Create Successful',201);
           

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }    
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
        return ApiResponseClass::sendResponse(new CoursResource($cours),'',200);
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
        try{
            $cours = $this->coursRepository->findById($id);
            $updatedCours = $this->coursRepository->update($request->validated(), $cours);

            return ApiResponseClass::sendResponse(new CoursResource($updatedCours),'Cours Updated Successful',201);
        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
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

        return ApiResponseClass::sendResponse('Cours Delete Successful','',200);
    }

    public function enrolle($id){

        try{
            $enrollement = Enrollement::create(['user_id'=> Auth::user()->id,'cours_id'=> $id,'progress'=> 'in_progress', ]);

            return ApiResponseClass::sendResponse(new EnrollementResource($enrollement),201);
            
        }catch(\Exception $ex){
                return ApiResponseClass::rollback($ex);
        }
    }

    public function enrollmentList($id)
{
    try {
        $students = Enrollement::where('cours_id', $id)->with('user')->get()->pluck('user.name');

        return ApiResponseClass::sendResponse(['Students' => $students], 201);
    } catch (\Exception $ex) {
        return ApiResponseClass::rollback($ex);
    }
}

}
