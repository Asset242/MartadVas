<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookContoller;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('log-record', [WebhookContoller::class, 'LogRequest']);
Route::get('get-all-record', [WebhookContoller::class, 'getAllLogs']);

