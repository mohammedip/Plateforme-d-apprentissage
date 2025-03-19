<?php

namespace App\Repositories;

use App\Models\User;


interface UserRepositoryInterface{


    public function findByEmail($email);

    public function findById($id);

    public function create(array $data);

    public function update(array $data, User $user);


}