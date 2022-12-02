<?php

namespace App\Http\Controllers;

use App\Http\Resources\HistoryResource;
use App\Models\CheckInOut;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function view_history($id)
    {
        $history = CheckInOut::where('file_id',$id)->get()->sortBy('created_at');
        return HistoryResource::collection($history);
    }
}
