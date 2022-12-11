<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Models\CheckInOut;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;

class FileOperationsController extends Controller
{
    public function readFile($uuid)
    {
        $file = File::where('uuid',$uuid)->get()[0];
        $filepath = public_path('files').'/'.$file->uuid.'.'.$file->extension;
        return Response::download($filepath,$file->name.".".$file->extension);
    }

    public function Check_in(Request $request,$uuid)
    {
        $file = File::where('uuid',$uuid)->get()[0];
        if($file->reserved)
        {
            return ['status'=>'failed','message'=>'File Is Already Reserved'];
        }
        $file->reserved = true;
        $file->reserved_by_id = $request->user()->id;
        $file->save();

        $history = new CheckInOut();
        $history->file_id = $file->id;
        $history->user_id = $request->user()->id;
        $history->operation = 'check-in';
        $history->save();

        return ['status'=>'success','file'=>new FileResource($file)];
    }

    public function bulk_check_in(Request $request)
    {
        if(File::whereIn('uuid',$request->uuids)->where('reserved',true)->exists())
        {
            return ['status'=>'failed','message'=>"Some Files Are Already Reserved"];
        }
        File::whereIn('uuid',$request->uuids)->update([
            'reserved'=>true,
            'reserved_by_id'=>$request->user()->id,
        ]);
        $files = DB::table('files')->select(['id'])->whereIn('uuid',$request->uuids)->get();
        $data = array();
        foreach($files as $file)
        {
            error_log($file->id);
            array_push($data,[
                'file_id'=>$file->id,
                'user_id'=>$request->user()->id,
                'operation'=>'check-in',
            ]);
        }

        CheckInOut::insert($data);
        
        return ['status'=>"success",'Message'=>"Files Have Been Checked-In"];
    }

    public function download_file(Request $request,$uuid)
    {
        $file = $file = File::where('uuid',$uuid)->get()[0];
        $filepath = public_path('files').'/'.$file->uuid.'.'.$file->extension;
        return Response::download($filepath,$file->name.'.'.$file->extension);
    }

    public function check_out(Request $request,$uuid)
    {
        $file = File::where('uuid',$uuid)->get()[0];
        $new = $request->file('file');
        if($file->extension != $new->extension())
        {
            return response()->json(['message'=>'this file is not the same extension as the original'],400);
        }
        if($file->reserved && ($file->reserved_by->id == $request->user()->id))
        {
            unlink(public_path('files\\'.$file->uuid.'.'.$file->extension));
            $new->move(public_path('files'),$file->uuid.'.'.$file->extension);
            $file->reserved = false;
            $file->reserved_by_id = null;
            $file->save();

            $history = new CheckInOut();
            $history->file_id = $file->id;
            $history->user_id = $request->user()->id;
            $history->operation = 'check-out';
            $history->save();

            return ['status'=>'success','message'=>'file saved'];
        }
        else if(!$file->reserved)
        {
            return ['status'=>'failed','message'=>'file is not reserved'];
        }
        else
        {
            return ['status'=>'failed','message'=>'file is not reserved by you'];
        }
    }
}
