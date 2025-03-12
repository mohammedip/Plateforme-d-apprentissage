<?php

namespace App\Repositories;

use App\Models\Tag;


interface TagRepositoryInterface{


    public function getAll();

    public function findById($id);

    public function create(array $data);

    public function update(array $data, Tag $tag);

    public function delete(Tag $tag);

}