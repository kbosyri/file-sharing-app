<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\File;

class Group extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user', relatedPivotKey: 'user_id');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'group_file', relatedPivotKey: 'file_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id','id');
    }
}
