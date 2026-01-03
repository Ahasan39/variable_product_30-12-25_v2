<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FrontendController;
use App\Http\Controllers\Frontend\MessageController;


Route::group(['namespace' => 'Api','prefix'=>'v1','middleware' => 'api'], function(){
    
     Route::get('app-config', [FrontendController::class, 'appconfig']);
     Route::get('slider', [FrontendController::class, 'slider']);
     Route::get('category-menu', [FrontendController::class, 'categorymenu']);
     Route::get('hotdeal-product', [FrontendController::class, 'hotdealproduct']);
     Route::get('homepage-product', [FrontendController::class, 'homepageproduct']);
     Route::get('footer-menu-left', [FrontendController::class, 'footermenuleft']);
     Route::get('footer-menu-right', [FrontendController::class, 'footermenuright']);
     Route::get('social-media', [FrontendController::class, 'socialmedia']);
     Route::get('contactinfo', [FrontendController::class, 'contactinfo']);
     
    //  Home Page Api End =================================
    
    Route::get('category/{id}', [FrontendController::class, 'catproduct']);
    
    // script pushing
    Route::get('push/script/{id?}', function ($id=1) {
        return response()->json(['id' => $id]);
    });
    

});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/chat/store', [MessageController::class, 'store']);


Route::post('/translate-proxy', function (Request $request) {
    $response = Http::post('https://libretranslate.de/translate', [
        'q' => $request->input('q'),
        'source' => $request->input('source', 'en'),
        'target' => $request->input('target', 'bn'),
        'format' => 'text',
    ]);
    
     dd($response->body());

    if ($response->successful()) {
        return response()->json($response->json());
    } else {
        return response()->json(['error' => 'Translation API error'], 500);
    }
});