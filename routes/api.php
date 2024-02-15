<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Nette\Utils\Json;
use App\Http\Controllers\GoogleSheetsAPI;
use App\Http\Controllers\Student;
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


Route::get('/values', function () {
    $student = new Student("11noy7IP6xjwcCidWczdvB1iNOw2_3TRlDmGbET35JXk", "4:28");
    return response()->json(($student->checkLackOfGrade()));
});

Route::get('/insert', function () {
    $student = new Student("11noy7IP6xjwcCidWczdvB1iNOw2_3TRlDmGbET35JXk", "4:27");
    $newValue = $student->checkLackOfGrade();
    $emptyArr = [];

    foreach ($newValue as $value) {
        array_push($emptyArr, [
            $value['situacao'],
            $value['naf']
        ]);
    }

    $google = new GoogleSheetsAPI("11noy7IP6xjwcCidWczdvB1iNOw2_3TRlDmGbET35JXk", "4:27");
    return $google->insertData("G4:H27", $emptyArr);
});


Route::get('/get/raw', function () {
    $student = new Student("11noy7IP6xjwcCidWczdvB1iNOw2_3TRlDmGbET35JXk", "4:27");
    return response()->json(($student->getData()));
});