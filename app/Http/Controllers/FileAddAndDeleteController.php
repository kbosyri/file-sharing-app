<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Models\CheckInOut;
use App\Models\File;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Str;

class FileAddAndDeleteController extends Controller
{
    public function AddNewFileToGroup(Request $request)
    {
        $new = new File();
        
        $file = $request->file('file');
        $new->uuid = Str::uuid();
        $new->name = $file->getClientOriginalName();
        $new->extension = $file->extension();
        $new->path = public_path('files');
        $new->owner_id = $request->user()->id;
        $new->save();
        $file->move(public_path('files'),$new->uuid.'.'.$file->extension());
        DB::table('group_file')->insert([
            'file_id'=>$new->id,
            'group_id'=>$request->group,
        ]);
        
        $history = new CheckInOut();
        $history->file_id = $new->id;
        $history->user_id = $request->user()->id;
        $history->operation = 'upload';
        $history->save();

        return new FileResource($new);
    }

    public function AddFileToGroup(Request $request, $group_id)
    {
        $file = File::where('uuid',$request->file_uuid)->get()[0];
        if($request->user()->id == $file->owner_id)
        {
            DB::table('group_file')->insert([
                'file_id'=>$file->id,
                'group_id'=>$group_id,
            ]);
            
        }

        return new FileResource($file);
    }

    public function deleteFile(Request $request)
    {
        error_log('test');
        error_log('uuid= '.$request->file_uuid);
        error_log('user_id'.$request->user()->id);
        $file = File::where('uuid',$request->file_uuid)->get()[0];
        
        error_log('File Delete Controller');
        if($file->reserved)
        {
            return response()->json(['message'=>'File Is Reserved'],400);
        }
        else if(!$file->owner_id != $request->user()->id)
        {
            return response()->json(['message'=>'You Are Not The File Owner'],400);
        }
        else if($request->user()->id == $file->owner_id && !$file->reserved)
        {
            error_log('entered If Statement');
            DB::table('group_file')->where('file_id','=',$file->id)->delete();
            unlink(public_path('files/'.$file->uuid.'.'.$file->extension));
            $file->delete();
            return response()->json(['message'=>'File Deleted']);
        }

        return response()->json(['message'=>'File Failed To Delete'],500);
    }

    public function deleteFileFromGroup(Request $request, $group_id)
    {
        $file = File::where('uuid',$request->file_uuid)->get()[0];
        if($request->user()->id == $file->owner_id && !$file->reserved)
        {
            DB::table('group_file')->where('file_id','=',$file->id)->where('group_id','=',$group_id)->delete();
        }

        return ['message'=>'File Deleted From Group'];
    }
}
