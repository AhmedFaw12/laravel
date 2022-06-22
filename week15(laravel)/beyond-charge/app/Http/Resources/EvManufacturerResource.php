<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EvManufacturerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    // public static $wrap = "mykey"; //applying my own wrap key
    public function toArray($request)
    {
        return [
            "m_id"=> $this->id,
            "m_name"=> $this->name,
            "ev_models_count"=>$this->ev_models->count(), //return a relation
        ];
    }
}
