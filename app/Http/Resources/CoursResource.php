<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
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
        return [
            'id' =>$this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category_id'=>$this->category ? $this->category->name : null,
            'mentor_id'=>$this->mentor ? $this->mentor->name : null,
        ];
    }
}
