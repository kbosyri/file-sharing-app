<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GroupFileResource;
use App\Http\Resources\GroupUserResource;
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
            'files' => GroupFileResource::collection($this->files),
            'users' => GroupUserResource::collection($this->users),
        ];
    }
}
