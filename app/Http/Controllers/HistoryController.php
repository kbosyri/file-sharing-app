<?php

namespace App\Http\Controllers;

use App\Http\Resources\HistoryResource;
use App\Models\CheckInOut;
use App\Models\File;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function view_history($uuid)
    {
        $file = File::where('uuid',$uuid)->get()[0];
        return HistoryResource::collection($file->history->sortByDesc('created_at'));
    }
}
