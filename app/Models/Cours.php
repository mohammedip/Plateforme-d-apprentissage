<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cours extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'mentor_id',

    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'cours_tags', 'cours_id', 'tag_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function enrollements()
    {
        return $this->hasMany(Enrollement::class, 'cours_id');
    }

}
