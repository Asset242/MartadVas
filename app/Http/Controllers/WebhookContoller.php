<?php

namespace App\Http\Controllers;

use App\Models\LogRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PartnerServiceReport;

class WebhookContoller extends Controller
{
  public function LogRequest(Request $request)
  {
      $data = $request->all();
  
      // Validate structure
      foreach ($data as $item) {}
  
      // Bulk Insert
      $insertData = array_map(function($item) {
        return [
          'service_type'  => $item['serviceType'] ?? null,
          'charge_amount' => $item['chargeAmount'] ?? null,
          'count'         => $item['count'] ?? null,
          'added_date'    => $item['added_date'] ?? null,
          'created_at'    => now(),
          'updated_at'    => now(),
      ];
      }, $data);
  
      PartnerServiceReport::insert($insertData);
  
      // Log raw JSON payload
      LogRequest::create([
          'payload' => json_encode($data),
      ]);
  
      return response()->json([
          'success' => true,
          'message' => 'Data Received Successfully',
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
