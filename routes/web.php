<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
Route::group(['middleware'=>'auth'],function(){
    //API for anyone or public
    Route::get('/','Page\HomeController@getHome')->where('any','.*');
    Route::get('/login',function(Request $request){
        if($request->wantsJson()){
            return response()->json(['status' => 'success', 'message' => 'OK']);
        }
        // return getView('login');
    });
    Route::get('/register',function(Request $request){
        if($request->wantsJson()){
            return response()->json(['status' => 'success', 'message' => 'OK']);
        }
        // return getView('register');
    });
    Route::get('/auth/redirect', 'Auth\LoginController@redirectToProvider');
    Route::get('/auth/google', 'Auth\LoginController@handleGoogleLogin');
    Route::post('/auth/google-tap', 'Auth\LoginController@handleGoogleLogin');
    Route::group(["prefix"=>"/verify"],function(){
        Route::group(['prefix'=>'/create'],function(){
            Route::post('/password','Services\MailController@createForgotPassword');
            Route::post('/email','Services\MailController@createVerifyEmail');
        });
        Route::group(['prefix'=>'/password'],function(){
            Route::get('/{any?}','UserController@getChangePass')->where('any','.*');
            Route::post('/','UserController@changePassEmail');
        });
        Route::group(['prefix'=>'/email'],function(){
            Route::get('/{any?}','UserController@verifyEmail')->where('any','.*');
            Route::post('/','UserController@verifyEmail');
        });
        Route::group(['prefix'=>'/otp'],function(){
            Route::post('/resend/password','Services\MailController@ResendOTP');
            Route::post('/resend/email','Services\MailController@ResendOTP');
            Route::post('/password','UserController@getChangePass');
            Route::post('/email','UserController@verifyEmail');
        });
    });
    Route::group(["prefix"=>"/password/reset"],function(){
        Route::get('/',function(Request $request){
            if($request->wantsJson()){
                return response()->json(['status' => 'success', 'message' => 'OK']);
            }
            return view();
        });
        Route::get('/change',function(){
            // return getView();
        });
    });

    //API only auth user
    Route::get('/profile','Page\HomeController@showProfile')->where('any','.*');
    Route::get('/dashboard','Page\HomeController@dashboard')->where('any','.*');
    Route::group(["prefix"=>"/users"],function(){
        Route::post('/login','Auth\LoginController@login');
        Route::post('/register','Auth\RegisterController@register');
        Route::post('/register/google', 'UserController@createGoogleUser');
        Route::post('/logout','UserController@logout');
        Route::get('/pengaturan',function(){
            // return getView('pengaturan');
        });
        Route::group(['prefix'=>'/update'], function(){
            Route::put('/profile','UserController@updateProfile');
            Route::put('/password','UserController@updatePassword');
        });
    });
});
Route::fallback(function(){
    $indexPath = public_path('dist/index.html');
    if (File::exists($indexPath)) {
        $htmlContent = File::get($indexPath);
        return response($htmlContent, 404);
    } else {
        // If the index.html file doesn't exist, return a 404 response
        return response()->json(['error' => 'Page not found'], 404);
    }
});