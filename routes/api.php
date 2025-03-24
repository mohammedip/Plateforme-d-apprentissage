<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MentorController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EtudiantController;
use App\Http\Controllers\Api\CoursController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\StatistiqueController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::apiResource('categories',CategoryController::class);  
    Route::apiResource('tags',TagController::class);
    Route::apiResource('courses',CoursController::class);
});
 Route::prefix('v2')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth:sanctum');
    Route::put('/profile', [ProfileController::class, 'update'])->middleware('auth:sanctum');
    Route::post('/profile', [ProfileController::class, 'update'])->middleware('auth:sanctum');
    Route::get('/stats/courses', [StatistiqueController::class, 'CoursStatistiques'])->middleware('auth:sanctum');
    Route::get('/stats/categories', [StatistiqueController::class, 'CategoryStatistiques'])->middleware('auth:sanctum');
    Route::get('/stats/tags', [StatistiqueController::class, 'TagStatistiques'])->middleware('auth:sanctum');
    Route::post('/courses/{id}/enroll', [CoursController::class, 'enrolle'])->middleware('auth:sanctum');    
    Route::post('/courses/{id}/enrollments', [CoursController::class, 'enrollmentList'])->middleware('auth:sanctum'); 
    Route::apiResource('videos',VideoController::class)->middleware('auth:sanctum');

    Route::get('/mentors/{id}/courses', [MentorController::class, 'getCourses']);
    Route::get('/mentors/{id}/students', [MentorController::class, 'getStudents']);
    Route::get('/mentors/{id}/performance', [MentorController::class, 'getPerformance']);
    Route::get('/students/{id}/courses', [EtudiantController::class, 'getCourses']);
    Route::get('/students/{id}/progress', [EtudiantController::class, 'getProgress']);

});