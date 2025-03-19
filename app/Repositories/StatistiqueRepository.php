<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Models\User;
use App\Models\Cours;
use App\Models\Category;


class StatistiqueRepository {

    public function getUsersCount(){

        return User::count();
    }

    public function getCategoriesCount(){

        return Category::count();
    }

    public function getTagsCount(){

        return Tag::count();
    }

    public function getCoursesCount(){

        return Cours::count();
    }






}