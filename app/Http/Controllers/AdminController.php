<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Http\Resources\GroupResource;
use App\Models\File;
use App\Models\Group;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function view_all_files()
    {
        $files = File::all();
        return FileResource::collection($files);
    }

    public function view_all_groups()
    {
        $groups = Group::all();
        return GroupResource::collection($groups);
    }
}
