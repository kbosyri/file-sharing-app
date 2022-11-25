<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserGroupResource;
use App\Http\Resources\UserFileResource;
use App\Models\User;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    protected $collects = User::class;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'admin'=> $this->when($this->admin,true,false),
            'files'=> UserFileResource::collection($this->owned_files),
            'reserved_files'=> UserFileResource::collection($this->reserved_files),
            'groups'=> UserGroupResource::collection($this->groups)
            
        ];
    }
}
