<?php

namespace App\Http\Controllers\Api;

use App\Models\Enrollement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EtudiantController extends Controller
{
    public function getCourses($id)
    {
        $courses = Enrollement::where('user_id', $id)->with('cours')->get();
        return response()->json($courses);
    }


    public function getProgress($id)
    {
        $progress = Enrollement::where('user_id', $id)->select('cours_id', 'progress')->get();
        return response()->json($progress);
    }
}
