<?php

namespace App\Http\Resources;

use App\Models\Group;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GroupFileResource;

class UserGroupResource extends JsonResource
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
        ];
    }
}
