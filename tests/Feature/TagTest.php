<?php

test(" can list tags",function(){

    $responce = $this->get("api/v1/tags");
    $responce->assertStatus(200);
    $responce->assertJsonStructure([
        "data" => [
            "*" => [
                'name',
            ],
        ],
    ]);
});