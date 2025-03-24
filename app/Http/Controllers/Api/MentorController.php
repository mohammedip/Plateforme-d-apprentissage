<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Cours;
use App\Models\Enrollement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MentorController extends Controller
{

    public function getCourses($id)
    {
        $courses = Cours::where('mentor_id', $id)->get();
        return response()->json($courses);
    }


    public function getStudents($id)
    {
        $students = User::whereHas('enrollements.cours', function ($query) use ($id) {
            $query->where('mentor_id', $id);
        })->get();

        return response()->json($students);
    }


    public function getPerformance($id)
    {
        $totalCourses = Cours::where('mentor_id', $id)->count();
        $totalStudents = Enrollement::whereHas('cours', function ($query) use ($id) {
            $query->where('mentor_id', $id);
        })->distinct('user_id')->count();

        return response()->json([
            'total_courses' => $totalCourses,
            'total_students' => $totalStudents
        ]);
    }
}
