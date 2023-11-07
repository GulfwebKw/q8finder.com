<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function (){

    Route::post('/test','GeneralController@test');
    Route::get('/test','GeneralController@test');
    Route::post('/is_valid','GeneralController@is_valid');
    // Route::get('/callback','AdvertisingController@paymentResult')->name('api.callback');


    Route::get('/settings','GeneralController@getSettings');
    Route::get('/getServiceCategories','GeneralController@getServiceCategories');
    Route::get('/getMarqueeText','GeneralController@getSettingMarqueeText');
    Route::get('/setting','GeneralController@getSetting');
    Route::get('/cities','GeneralController@getCities');
    Route::get('/areas','GeneralController@getAreas');
    Route::get('/packages','GeneralController@getPackages');
    Route::get('/static-packages','GeneralController@getStaticPackages');
    Route::get('/get-search-property','GeneralController@getSearchProperty');


    Route::post('/register','UserController@register');
    Route::post("/register/sendOTP",'UserController@registerSendOTP');
    Route::post('/login','UserController@login');
    Route::get("/formPayment",'AdvertisingController@formPayment')->name('api.formPayment');



    Route::get('/getListAdvertising','AdvertisingController@getListAdvertising');
    Route::post('/search-advertising','AdvertisingController@search');
    Route::get('/advertising/{id}','AdvertisingController@getAdvertising');
    Route::get('/advertising/{id}/addView','AdvertisingController@addView');
    Route::get('/advertising/{id}/report','AdvertisingController@report');
    Route::get('/similarAdvertising/{id}','AdvertisingController@similarAdvertising');
    Route::get('/companies','AdvertisingController@companies');
    Route::get('/company/{id}','AdvertisingController@company');
    Route::get('/company/{id}/report','UserController@report');



    Route::get('/getListService','AdvertisingController@getListAdvertising')->middleware('serviceScope');
    Route::post('/search-service','AdvertisingController@search')->middleware('serviceScope');
    Route::get('/service/{id}','AdvertisingController@getAdvertising')->middleware('serviceScope');
    Route::get('/service/{id}/addView','AdvertisingController@addView')->middleware('serviceScope');
    Route::get('/service/{id}/report','AdvertisingController@report')->middleware('serviceScope');
    Route::get('/similarService/{id}','AdvertisingController@similarAdvertising')->middleware('serviceScope');

    Route::group(["prefix"=>"user"],function (){
        Route::get('/login','UserController@unAuthorize')->name('unAuthorize');
        Route::post("/verifyUserBySmsCode",'UserController@verifyUserBySmsCode');
        Route::post("/resetPassword",'UserController@resetPassword');
        Route::post('/sendResetPasswordCode','UserController@sendRequestSmsCode');
        Route::post("/sendSmsCode",'UserController@sendSmsCode');
        Route::post("/logVisitAdvertising",'AdvertisingController@logVisitAdvertising');
        Route::post("/logVisitService",'AdvertisingController@logVisitAdvertising')->middleware('serviceScope');

        Route::group(['middleware' => 'auth:api'], function (){
            Route::get('/user', function (Request $request) { return $request->user(); });
            Route::get('/profile', 'UserController@profile');
            Route::get("/getBalance",'UserController@getBalance');
            Route::get("/payments",'UserController@payments');

            Route::post("/isValidRegisterAdvertising",'UserController@isValidRegisterAdvertising');
            Route::post("/isValidRegisterService",'UserController@isValidRegisterAdvertising')->middleware('serviceScope');
            Route::post("/updateLanguage",'UserController@updateLanguage');
            Route::post("/saveLicense",'UserController@saveLicense');


            Route::get("/getSavedAdvertising",'AdvertisingController@getUserSaved');
            Route::get("/advertising",'AdvertisingController@getUserAdvertising');
            Route::get("/getSavedService",'AdvertisingController@getUserSaved')->middleware('serviceScope');
            Route::get("/Service",'AdvertisingController@getUserAdvertising')->middleware('serviceScope');
            Route::post("/buyPackageOrCredit",'AdvertisingController@buyPackageOrCredit');
            Route::get("/upgradeCompanyToPremium",'AdvertisingController@buyCompanyPremium');
            Route::post("/advertising/upgrade",'AdvertisingController@upgrade_premium');
            Route::post("/advertising/create",'AdvertisingController@createAdvertising')->name('api.createAdvertise');
            Route::post("/advertising/repost",'AdvertisingController@repostAdvertising')->name('api.repostAdvertise');
            Route::post("/advertising/attachFileToAdvertising",'AdvertisingController@attachFileToAdvertising');
            Route::post("/advertising/deleteFileFromAdvertising",'AdvertisingController@deleteFileFromAdvertising');
            Route::post("/advertising/update",'AdvertisingController@updateAdvertising');
            Route::post("/advertising/delete",'AdvertisingController@deleteAdvertising');
            Route::post("/advertising/archive",'AdvertisingController@archiveAdvertising');
            Route::post("/advertising/detachArchive",'AdvertisingController@detachArchive');

            Route::group(['prefix' => 'service' , 'middleware' => 'serviceScope'] , function () {
                Route::post("/upgrade",'AdvertisingController@upgrade_premium');
                Route::post("/create",'AdvertisingController@createAdvertising')->name('api.createService');
                Route::post("/repost",'AdvertisingController@repostAdvertising')->name('api.repostService');
                Route::post("/attachFileToService",'AdvertisingController@attachFileToAdvertising');
                Route::post("/deleteFileFromService",'AdvertisingController@deleteFileFromAdvertising');
                Route::post("/update",'AdvertisingController@updateAdvertising');
                Route::post("/delete",'AdvertisingController@deleteAdvertising');
                Route::post("/archive",'AdvertisingController@archiveAdvertising');
                Route::post("/detachArchive",'AdvertisingController@detachArchive');
            });

            Route::post("/upgrade",'UserController@upgrade');
            Route::get("/downgrade",'UserController@downgrade');
            Route::post("/updateProfile",'UserController@updateProfile');
            Route::post("/deleteAccount",'UserController@deleteAccount');
            Route::post("/updateDeviceToken",'UserController@updateDeviceToken');
            Route::post("/changePassword",'UserController@changePassword');

        });
    });

    Route::post('/set-ticket','ContactUsController@store');


});
