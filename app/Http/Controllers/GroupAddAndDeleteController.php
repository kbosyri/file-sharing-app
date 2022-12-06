<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupAddAndDeleteController extends Controller
{
    public function creategroup(Request $request)
    {
        $new = new Group();
        $new->name = $request->name;
        $new->save();
        $users = Array();
        array_push($users,["user_id"=>$request->user()->id,'group_id'=>$new->id]);
        DB::table('group_user')->insert($users);

        return new GroupResource($new);
    }

    public function deletegroup($group)
    {
        $main = Group::where('id',$group)->get()[0];
        foreach($main->files as $file)
        {
            if($file->reserved)
            {
                return ['message'=>'The Group Has A Checked-In File',"status"=>'failed'];
            }
        }
        DB::table('group_user')->where('group_id','=',$group)->delete();
        Group::find($group)->delete();
        return ['messasge'=>"group Deleted",'status'=>'success'];
    }

    public function addusers(Request $request,$group)
    {

        if(!User::where('email',$request->email)->exists())
        {
            error_log('testing Controller');
            return response()->json(['message'=>'This E-mail Does Not Exist'],400);
        }

        $user = User::where('email',$request->email)->get()[0];

        if(DB::table('group_user')->where('user_id','=',$user->id)->where('group_id','=',$group)->exists())
        {
            return response()->json(['message'=>'This User Is Already In This Group'],400);
        }

        DB::table('group_user')->insert(["user_id"=>$user->id,'group_id'=>$group]);

        return new GroupResource(Group::find($group));
    }

    public function deleteuser(Request $request,$group)
    {
        $main = Group::find($group);
        foreach($main->files as $file)
        {
            if($file->reserved_by->id == $request->user_id)
            {
                return ["status"=>'failed','message'=>'This User Has A File Reserved In This Group'];
            }
        }
        DB::table('group_user')->where('user_id','=',$request->user_id)->where('group_id','=',$group)->delete();

        return ["status"=>'success',"message"=>'User Have Been Deleted'];
    }
}
