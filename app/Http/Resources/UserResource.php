<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\FileResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'admin'=> $this->when($this->admin,true,false),
            'files'=> FileResource::collection($this->owned_files),
            'reserved_files'=> FileResource::collection($this->reserved_files),
            'groups'=> GroupResource::collection($this->groups)
            
        ];
    }
}
