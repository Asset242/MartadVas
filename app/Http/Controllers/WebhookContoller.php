<?php

namespace App\Http\Controllers;

use App\Models\LogRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebhookContoller extends Controller
{
    public function LogRequest(Request $request){
        $data = $request->all();
        $newData = new LogRequest();
        $newData->payload = $request->all();
        $newData->save();
        return response()->json([
            'success' => true,
            'message' => 'Data Received Successfull',
            'data' => $newData
        ], 200);
  }

  public function getAllLogs (Request $request) {
    $logs = LogRequest::all();
    return response()->json([
        'success' => true,
        'message' => 'Data retrieved successfully',
        'data' => $logs 
    ], 200);
  }
}
