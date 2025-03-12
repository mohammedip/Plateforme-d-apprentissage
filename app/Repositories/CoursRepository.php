<?php

namespace App\Repositories;

use App\Models\Cours;
use App\Repositories\CoursRepositoryInterface;

class CoursRepository implements CoursRepositoryInterface{

    public function getAll(){

        return Cours::get();
    }

    public function findById($id){

        return Cours::findOrFail($id);
    }

    public function create(array $data){

        return Cours::create($data);
    }

    public function update(array $data, Cours $cours){

        $cours->update($data);
        return $cours;
    }

    public function delete(Cours $cours){

        return $cours->delete();
    }





}