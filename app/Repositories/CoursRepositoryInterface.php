<?php

namespace App\Repositories;

use App\Models\Cours;
use Illuminate\Http\Request;


interface CoursRepositoryInterface{


    public function getAll();

    public function findById($id);

    public function create(array $data);

    public function update(array $data, Cours $cours);

    public function delete(Cours $cours);

}