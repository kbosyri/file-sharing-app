<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupFileResource;
use App\Http\Resources\UserFileResource;
use App\Http\Resources\UserGroupResource;
use App\Models\Group;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    public function userfiles(Request $request)
    {
        return UserFileResource::collection($request->user()->owned_files);
    }

    public function groupfiles($group)
    {
        $main = Group::find($group);
        return GroupFileResource::collection($main->files);
    }

    public function usergroups(Request $request)
    {
        return UserGroupResource::collection($request->user()->groups);
    }

    public function userfilesreserved(Request $request)
    {
        return UserFileResource::collection($request->user()->reserved_files);
    }
}
