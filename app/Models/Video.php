<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';

    protected $fillable = [
        'cours_id',
        'title',
        'description',
        'url',
    ];

    public function cours(){
        return $this->belongsTo(Cours::class, 'cours_id');
    }
}
