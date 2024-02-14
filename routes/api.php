<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Nette\Utils\Json;
use App\Http\Controllers\GoogleSheetsAPI;
use GuzzleHttp\Psr7\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/values', function(){
    $googleSheets = new GoogleSheetsAPI("11noy7IP6xjwcCidWczdvB1iNOw2_3TRlDmGbET35JXk", "3:28");
    return response()->json(($googleSheets->handleRowsResult()));
});