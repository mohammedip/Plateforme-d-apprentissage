<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollement extends Model
{
    protected $table = 'enrollements';

    protected $fillable = [
        'cours_id',
        'user_id',
        'status',
        'progress',
    ];


    public function cours()
    {
        return $this->belongsTo(Cours::class, 'cours_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}

