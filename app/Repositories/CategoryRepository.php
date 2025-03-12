<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\CategoryRepositoryInterface;


class CategoryRepository implements CategoryRepositoryInterface{

    public function getAll(){

        return Category::get();
    }

    public function findById($id){

        return Category::findOrFail($id);
    }

    public function create(array $data){

        return Category::create($data);
    }

    public function update(array $data, Category $category){

        $category->update($data);
        return $category;
    }

    public function delete(Category $category){

        return $category->delete();
    }





}