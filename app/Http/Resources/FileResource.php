<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FileGroupResource;
use App\Http\Resources\FileUserResource;
use App\Models\File;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    protected $collects = File::class;
    
    public function toArray($request)
    {
        error_log('Before Resource');
        $array =[
            'id'=> $this->id,
            'name'=> $this->name,
            'uuid'=> $this->uuid,
            'path'=> $this->path,
            'owner'=> new FileUserResource($this->owner),
            'reserved'=> $this->when($this->reserved,true,false),
            'reserved_by'=>$this->when($this->reserved,new FileUserResource($this->reserved_by),null),
            'groups'=>FileGroupResource::collection($this->groups),
        ];
        error_log('after Resource');
        return $array;
    }
}
