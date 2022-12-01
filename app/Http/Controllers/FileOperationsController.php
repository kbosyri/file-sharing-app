<?php

namespace App\Http\Controllers;

use App\Exceptions\FileReserved;
use App\Exceptions\FileReservedException;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;

class FileOperationsController extends Controller
{
    public function readFile($uuid)
    {
        $file = File::where('uuid',$uuid)->get()[0];
        $filepath = public_path('files').'/'.$file->uuid.$file->extension;
        return Response::download($filepath,$file->name);
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
        return ['status'=>'success','file'=>new FileResource($file)];
    }

    public function bulk_check_in(Request $request)
    {
        foreach($request->uuids as $uuid)
        {
            $file = File::where('uuid',$uuid)->get()[0];
            if($file->reserved)
            {
                return ['status'=>'failed','message'=>'File Is Already Reserved'];
            }
            $file->reserved = true;
            $file->reserved_by_id = $request->user()->id;
            $file->save();
        }
        return ['status'=>"success",'Message'=>"Files Have Been Checked-In"];
    }

    public function download_file(Request $request,$uuid)
    {
        $file = $file = File::where('uuid',$uuid)->get()[0];
        return ['status'=>'success','file'=>URL::asset('file/'.$file->uuid.'.'.$file->extension)];
    }
}
