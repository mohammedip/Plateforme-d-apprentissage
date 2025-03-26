<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Cours;
use App\Models\Enrollement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepositoryInterface;

class MentorController extends Controller
{
 

    public function getCourses()
    {
        $user = Auth::user();
        if($user->role== "mentor"){

            $courses = Cours::where('mentor_id', $user->id)->get();
            return response()->json($courses);
            
        }else{
            return response()->json("This is mentor space login with a studant account to get access to this page");
        }
    }


    public function getStudents($id)
    {
        $user = Auth::user();
        if($user->role== "mentor"){


            $students = User::whereHas('enrollements.cours', function ($query) use ($user) {
                $query->where('mentor_id', $user->id);
            })->get();

            return response()->json($students);

        }else{
            return response()->json("This is mentor space login with a studant account to get access to this page");
        }
    }


    public function getPerformance($id)
    {
        $user = Auth::user();
        if($user->role== "mentor"){


            $totalCourses = Cours::where('mentor_id', $user->id)->count();
            $totalStudents = Enrollement::whereHas('cours', function ($query) use ($user) {
                $query->where('mentor_id', $user->id);
            })->distinct('user_id')->count();

            return response()->json([
                'total_courses' => $totalCourses,
                'total_students' => $totalStudents
            ]);

        }else{
            return response()->json("This is mentor space login with a studant account to get access to this page");
        }
    }
}
