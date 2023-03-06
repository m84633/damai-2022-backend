<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('quiz/exchange',function(Request $request){
    if((!$request->from || !$request->to)){
        return false;
    }

    $allow_currency = collect(['USD','TWD','JPY']);
    $from = strtoupper((string)$request->from);
    $to = strtoupper((string)$request->to);
    if(($request->from == $request->to) ||  !$allow_currency->contains($from) || !$allow_currency->contains($to)){
        return flase;//參數錯誤
    }

    $content = Http::get('https://tw.rter.info/capi.php');
    $result = $content->json($from.$to);
    return response()->json([
                                'exchange_rate' => (float)$result['Exrate'],
                                'updated_at'    => (string)$result['UTC']
                            ]);
});

