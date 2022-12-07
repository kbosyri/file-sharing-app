<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
use App\Models\User;
use App\Models\CheckInOut;

class File extends Model
{
    use HasFactory;

    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id','id');
    }

    public function reserved_by()
    {
        return $this->belongsTo(User::class,'reserved_by_id','id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_file', relatedPivotKey: 'group_id');
    }

    public function history()
    {
        return $this->hasMany(CheckInOut::class,'file_id','id');
    }
}
