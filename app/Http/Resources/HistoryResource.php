<?php

namespace App\Http\Resources;

use App\Models\CheckInOut;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\HistoryFileResource;
use App\Http\Resources\HistoryUserResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    protected $collects = CheckInOut::class;
    
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'file'=>new HistoryFileResource($this->file),
            'user'=>new HistoryUserResource($this->user),
            'operation'=>$this->operation,
            'created_at'=>$this->created_at,
        ];
    }
}
