<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Repositories\UserRepositoryInterface;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(UserRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request){

        try{
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
    
             $this->authRepository->create($data);
            $user = $this->authRepository->findByEmail($request->email);

            $validRoles = ['admin', 'mentor', 'etudiant'];
            $role = in_array($data['role'] ?? 'etudiant', $validRoles) ? $data['role'] : 'etudiant';
            $user->assignRole($role);
            
            $token = $user->createToken($request->email);
            
            return ApiResponseClass::sendResponse(['user' => new UserResource($user), 'token' => $token->plainTextToken],'Register Successfully',201);
            
        }catch(\Exception $ex){
                return ApiResponseClass::rollback($ex);
        }

    }

    public function login(LoginRequest $request){

        try{

        $user = $this->authRepository->findByEmail($request->email);

        if(!$user || !Hash::check($request->password, $user->password)){
        
            return ['message' => 'login information are incorrect']; 
        }
            
        $token = $user->createToken($user->email);
            
        return ApiResponseClass::sendResponse(['user' => new UserResource($user), 'token' => $token->plainTextToken],'Login Successfully',200);
        
            
        }catch(\Exception $ex){
                return ApiResponseClass::rollback($ex);
        }
    }

    public function logout(Request $request){

        $request->user()->tokens()->delete();
        return ['message' => 'Logout Successfully']; 
    }

    public function refresh(Request $request){

        try{
            $request->user()->tokens()->delete();

            $user = $this->authRepository->findByEmail($request->user()->email);
                
            $token = $user->createToken($user->email);
                
            return ApiResponseClass::sendResponse(['user' => new UserResource($user), 'token' => $token->plainTextToken],'Token refreshed Successfully',200);
            
                
            }catch(\Exception $ex){
                    return ApiResponseClass::rollback($ex);
            }

    }
}
