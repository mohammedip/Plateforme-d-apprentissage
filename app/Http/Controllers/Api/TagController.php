<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Repositories\TagRepositoryInterface;

/**
 * @OA\PathItem(path="127.0.0.1:8000/api/v1/categories")
 */

class TagController extends Controller
{
    protected $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

      /**
     * @OA\Get(
     *     path="127.0.0.1:8000/api/v1/tags",
     * tags={"tags"},
     *     summary="Get all tags",
     *     @OA\Response(response="200", description="A Tag endpoint")
     * )
     */
    public function index()
    {
        $tags=$this->tagRepository->getAll();

        if($tags->count() > 0){
            
            return ApiResponseClass::sendResponse(TagResource::collection($tags),'',200);
        }else{

            return response()->json(['message'=>'No tags for the moment'],200);
        }
    }



 /**
     * @OA\Post(
     *     path="/api/v1/tags",
     * tags={"tags"},
     *     summary="Create a new tag",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Electronics"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tag created successfully")
     * )
     */
    public function store(StoreTagRequest $request)
    {
        try{
            $tags = collect(explode(',', $request->input('tags')))
            ->map(fn($tag) => ['name' => trim($tag)])
            ->unique() 
            ->values() 
            ->toArray();    
            $this->tagRepository->create($tags);

            return response()->json([

                'message'=>'Tag created successfully',
            ],201);
        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }    
    }

  /**
     * @OA\Get(
     *     path="/api/v1/tags/{id}",
     * tags={"tags"},
     *     summary="Get tag by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the tag",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *         )
     *     ),
     *     @OA\Response(response=404, description="Tag not found")
     * )
     */
    public function show(Tag $tag)
    {
        return ApiResponseClass::sendResponse(new TagResource($tag),'',200);
    }


/**
     * @OA\Put(
     *     path="/api/v1/tags/{id}",
     * tags={"tags"},
     *     summary="Updates a tag",
     *     @OA\Parameter(
     *         description="ID of the tag to update",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *        ),
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={ "name"},
     *             @OA\Property(property="name", type="string", example="Advanced Mathematics"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag  updated successfully"
     *     ),
     * @OA\Response(response=404, description="Tag not found")
     * )
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        try{
            
            $updatedTag=$this->tagRepository->update($request->validated(),$tag);

           
            return ApiResponseClass::sendResponse(new TagResource($updatedTag),'Tag Updated Successful',201);
        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

 /**
     * @OA\Delete(
     *     path="/api/v1/tags/{id}",
     * tags={"tags"},
     *     summary="Delete a tag",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the tag to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag deleted successfully"
     *     )
     * )
     */
    public function destroy(Tag $tag)
    {
        $this->tagRepository->delete($tag);
        return ApiResponseClass::sendResponse('Tag Delete Successful','',200);
    }
}
