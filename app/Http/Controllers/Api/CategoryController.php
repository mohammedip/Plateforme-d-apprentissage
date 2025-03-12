<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepositoryInterface;

/**
 * @OA\PathItem(path="127.0.0.1:8000/api/v1/categories")
 */
class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @OA\Get(
     *     path="127.0.0.1:8000/api/v1/categories",
     * tags={"categories"},
     *     summary="Get all categories",
     *     @OA\Response(response="200", description="A Category endpoint")
     * )
     */
    public function index()
    {
        $categories = $this->categoryRepository->getAll();

        if ($categories->count() > 0) {
            return ApiResponseClass::sendResponse(CategoryResource::collection($categories),'',200);
        } else {
            return response()->json(['message' => 'No category for the moment'], 200);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     * tags={"categories"},
     *     summary="Create a new category",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Electronics"),
     *             @OA\Property(property="category_id", type="integer", nullable=true, example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Category created successfully")
     * )
     */
    public function store(CategoryRequest $request)
    {
        try{
            $category = $this->categoryRepository->create($request->validated());

            return ApiResponseClass::sendResponse(new CategoryResource($category),'Product Create Successful',201);
            
        }catch(\Exception $ex){
                return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{id}",
     * tags={"categories"},
     *     summary="Get category by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="category_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function show(Category $category)
    {
        return ApiResponseClass::sendResponse(new CategoryResource($category->load('subCategories', 'parentCategories')),'',200);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/categories/{id}",
     * tags={"categories"},
     *     summary="Updates a category",
     *     @OA\Parameter(
     *         description="ID of the category to update",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *        ),
     * @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={ "name", "category_id"},
     *             @OA\Property(property="name", type="string", example="Advanced Mathematics"),
     *             @OA\Property(property="category_id", type="integer", example=9),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="category  updated successfully"
     *     ),
     * @OA\Response(response=404, description="Category not found")
     * )
     */
    public function update(CategoryRequest $request, Category $category)
    {
        try{
            $updatedCategory = $this->categoryRepository->update($request->validated(), $category);

            return response()->json([
                'message' => 'Category is updated successfully',
                'data' => new CategoryResource($updatedCategory)
            ], 200);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }    
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{id}",
     * tags={"categories"},
     *     summary="Delete a category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully"
     *     )
     * )
     */
    public function destroy(Category $category)
    {
        $this->categoryRepository->delete($category);

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
