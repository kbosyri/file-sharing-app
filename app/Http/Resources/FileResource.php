<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;

class FileResource extends JsonResource
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
            'id'=> $this->id,
            'name'=> $this->name,
            'uuid'=> $this->uuid,
            'path'=> $this->path,
            'owner'=> new UserResource($this->owner),
            'reserved'=> $this->when($this->reserved,true,false),
            'reserved_by'=>$this->when($this->reserved,new UserResource($this->reserved_by),'none'),
            'groups'=>GroupResource::collection($this->groups),
        ];
    }
}
