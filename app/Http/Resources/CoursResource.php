<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class CoursResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $user = Auth::user();

        $isEnrolled = $this->enrollements()->where('user_id', $user->id)->exists(); 

        return [
            'id' =>$this->id,
            'title' => $this->title,
            'description' => $this->description,
            'difficulty' => $this->difficulty,
            'category_id'=>$this->category ? $this->category->name : null,
            'mentor_id'=>$this->mentor ? $this->mentor->name : null,
            'videos' => $isEnrolled ? $this->videos : [],
        ];
    }
}
