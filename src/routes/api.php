<?php

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

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');
// Route::resource('projects', 'ProjectAPIController');

Route::get('projects/{id}/responses',['as' => 'project.responses', 'uses' => 'ProjectAPIController@responses']);

Route::get('projects/{id}/incidents',['as' => 'project.incidents', 'uses' => 'ProjectAPIController@getNewIncidents']);


Route::get('sms/status', ['as' => 'api-status', 'uses' => 'SmsAPIController@apiStatus']);

Route::match(['get','post'], 'sms/echo', ['as' => 'api-status', 'uses' => 'SmsAPIController@echoResponse']);

Route::match(['get', 'post'], 'sms', ['as' => 'recieve-sms', 'uses' => 'SmsAPIController@recieveSms']);

Route::group(['middleware' => 'auth:api'], function () {
    //Route::resource('sms', 'SmsAPIController');
});


Route::get('smscount/{project_id}/{section}', ['as' => 'smscount', 'uses' => 'ProjectAPIController@smscount']);

