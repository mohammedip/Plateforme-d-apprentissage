<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\TagRepositoryInterface;

class TagRepository implements TagRepositoryInterface{

    public function getAll(){

        return Tag::get();
    }

    public function findById($id){

        return Tag::findOrFail($id);
    }

    public function create(array $data){

        return Tag::insert($data);
    }

    public function update(array $data, Tag $tag){

        $tag->update($data);
        return $tag;
    }

    public function delete(Tag $tag){

        return $tag->delete();
    }





}