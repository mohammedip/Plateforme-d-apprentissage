<?php

namespace App\Repositories;

use App\Models\Category;

interface CategoryRepositoryInterface{


    public function getAll();

    public function findById($id);

    public function create(array $data);

    public function update(array $data, Category $category);

    public function delete(Category $category);

}