<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RequestLog;

class ResponseLog extends Model
{
    use HasFactory;

    public function request()
    {
        return $this->belongsTo(RequestLog::class,'request_id','id');
    }
}
