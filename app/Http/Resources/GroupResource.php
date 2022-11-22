<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FileResource;
use App\Http\Resources\UserResource;
use App\Models\Group;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    protected $collects = Group::class;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name'=> $this->name,
            'files' => FileResource::collection($this->files),
            'users' => UserResource::collection($this->users),
        ];
    }
}
