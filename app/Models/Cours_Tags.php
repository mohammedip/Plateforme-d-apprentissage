<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cours_Tags extends Model
{
    protected $table = 'cours_tags';

    protected $fillable = [
        'course_id',
        'tag_id',
    ];
}
