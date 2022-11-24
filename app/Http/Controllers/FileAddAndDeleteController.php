<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
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
        
        error_log('before database1');
        $file = $request->file('file');
        $new->uuid = Str::uuid();
        $new->name = $file->getClientOriginalName();
        $new->extension = $file->extension();
        $new->path = public_path('files');
        $new->owner_id = $request->user()->id;
        $new->reserved_by = 0;
        $new->save();
        error_log('after database1');
        $file->move(public_path('files'),$new->uuid.'.'.$file->extension());
        error_log('before database2');
        DB::table('group_file')->insert([
            'file_id'=>$new->id,
            'group_id'=>$request->group,
        ]);
        error_log('after database2');

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
        $file = File::where('uuid',$request->file_uuid)->get()[0];
        if($request->user()->id == $file->owner_id && !$file->reserved)
        {
            DB::table('group_file')->where('file_id','=',$file->id)->delete();
            FacadesFile::delete(public_path('files').$file->uuid.'.'.$file->extension);
            $file->delete();
        }

        return ['message'=>'File Deleted'];
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
