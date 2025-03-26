<?php

namespace App\Http\Controllers\Api;

use App\Models\Enrollement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepositoryInterface;

class EtudiantController extends Controller
{

  
    
    public function getCourses()
    {
        $user = Auth::user();
        if($user->role== "etudiant"){

            $courses = Enrollement::where('user_id', $user->id)->with('cours')->get();
            return response()->json($courses);

        }else{
            return response()->json("This is studant space login with a studant account to get access to this page");
        }
        
    }


    public function getProgress()
    {
        $user = Auth::user();
        if($user->role== "etudiant"){

            $progress = Enrollement::where('user_id', $user->id)->select('cours_id', 'progress')->get();
            return response()->json($progress);
            
        }else{
            return response()->json("This is studant space login with a studant account to get access to this page");
        }
    }
}
