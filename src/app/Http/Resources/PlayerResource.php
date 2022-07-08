<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {       
        if(count($this->resource) > 0 ){
            return [
                'player_id' => $this->resource[0]->player_id,
                'stats' => StatResource::collection($this->resource)
                ];
        }
            
        return [];
    }
}
