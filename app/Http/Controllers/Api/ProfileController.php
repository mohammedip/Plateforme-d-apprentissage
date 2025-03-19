<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfileRequest;
use App\Repositories\UserRepositoryInterface;

class ProfileController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(){

        // $user = $this->userRepository->findById(Auth::user()->id);
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        return ApiResponseClass::sendResponse(new UserResource($user),200);
        

    }

    public function update(UpdateProfileRequest $request){

        try{
            $user = Auth::user();

            $data = $request->validated();


            if ($request->hasFile('profile_image')) {
                $imagePath = $request->file('profile_image')->store('images', 'public');
                $data['profile_image'] = $imagePath;

            }
    
            $updatedUser = $this->userRepository->update($data, $user);
            return ApiResponseClass::sendResponse(new UserResource($updatedUser),'Your profile is Updated Successful',201);
        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }

    }





}
