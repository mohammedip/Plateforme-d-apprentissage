<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Cours;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchFiltrageController extends Controller
{
    public function searchByTitleOrDescription(Request $request){

        $query = $request->query('search');  

        $courses = Cours::where('title', 'like', "%$query%")
                        ->orWhere('description', 'like', "%$query%")
                        ->get();

        return response()->json( ["courses" => $courses],200);
    }

    public function searchByCategoryAndDifficulty(Request $request){

        $category_id = $request->query('category');  
        $difficulty = $request->query('difficulty'); 

        $courses = Cours::whereHas('category', function ($query) use ($category_id) {  
            $query->where('id', $category_id);
        })->where('difficulty',$difficulty)->get(); 

        return response()->json( ["courses" => $courses],200);
    }

    public function searchMentorByName(Request $request){

        $query = $request->query('search');  

        $mentors = User::where('name', 'like', "%$query%")->get();

        return response()->json( ["courses" => $mentors],200);
    }
}
