<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupFileResource;
use App\Http\Resources\GroupUserResource;
use App\Http\Resources\UserFileResource;
use App\Http\Resources\UserGroupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DisplayController extends Controller
{
    public function userfiles(Request $request)
    {
        return UserFileResource::collection($request->user()->owned_files);
    }

    public function groupfiles($group)
    {
        $main = "";
        error_log("Entered Group Files");
        if(Cache::has('group'.$group))
        {
            $main = Cache::get('group'.$group);
            
        }
        else
        {
            Cache::put('group'.$group,GroupFileResource::collection(Group::find($group)->files), now()->addSeconds(30));
            $main = Cache::get('group'.$group);
        }
        return $main;
    }

    public function groupusers($group)
    {
        $main = Group::find($group);
        return GroupUserResource::collection($main->users);
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
