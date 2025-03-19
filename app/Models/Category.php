<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'category_id',
    ];

    public function subCategories(){

        return $this->hasMany(Category::class, 'category_id');

    }

    public function courses(){

        return $this->hasMany(Cours::class);

    }


    public function parentCategories(){

        return $this->belongsTo(Category::class, 'category_id');
    }
}
