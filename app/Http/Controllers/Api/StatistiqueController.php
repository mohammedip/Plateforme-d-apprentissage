<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\StatistiqueRepository;

class StatistiqueController extends Controller
{
    protected $statistiqueRepository;

    public function __construct(StatistiqueRepository $statistiqueRepository)
    {
        $this->statistiqueRepository = $statistiqueRepository;
    }

    // public function UserStatistiques(){
    //     $userCount = $this->statistiqueRepository->getUsersCount();

    //     return ApiResponseClass::sendResponse(["User Count " => $userCount],200);
    // }

    public function CoursStatistiques(){
        $coursCount = $this->statistiqueRepository->getCoursesCount();

        return ApiResponseClass::sendResponse(["Cours Count " => $coursCount],200);
    }

    public function CategoryStatistiques(){
        $categoryCount = $this->statistiqueRepository->getCategoriesCount();

        return ApiResponseClass::sendResponse(["Category Count " => $categoryCount],200);
    }

    public function TagStatistiques(){
        $tagCount = $this->statistiqueRepository->getTagsCount();

        return ApiResponseClass::sendResponse(["Tag Count " => $tagCount],200);
    }
}
