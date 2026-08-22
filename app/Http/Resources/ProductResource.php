<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   
        
       return[
            'id' => $this->id,
            'title' => $this->name,
            'price' => (float) $this->selling_price,
            'category' => $this->category->name,
            'brand'=>$this->brand->title,
           'image' => $this->image
            ? asset('storage/' . ltrim($this->image, '/'))
            : null,
            "description"=>$this->description,
            "reviews"=>
            ['rate'=>$this->reviews->avg("rating")??0,
              "count"=>    $this->reviews->count()
            ],

           
        ];
    }
}