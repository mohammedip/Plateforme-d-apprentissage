<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface{


    public function findByEmail($email){

        return User::where('email',$email)->first();
    }

    public function findById($id){

        return User::findOrFail($id);
    }

    public function create(array $data){

        $user = User::insert($data);
        return $user;
    }

    public function update(array $data, User $user){

        $user->update($data);
        return $user;
    }






}