<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('common')->namespace('Common')->group(function () {
	Route::post('/leads/listLeadsAjax', 'CommonController@listLeadsAjax');
    Route::post('/leads/addNotes', 'CommonController@addNotes');
    Route::post('/leads/delete/{id}', 'CommonController@deleteLeads');
    Route::post('/leads/changeStatus', 'CommonController@changeStatus');
    Route::post('/getAgencyAgent', 'CommonController@getAgencyAgent');
    Route::post('/send_chat_msg', 'ChatController@send_chat_msg');
    Route::post('/createGroup', 'ChatController@createGroup');
    Route::post('/getChatMessage', 'ChatController@getChatMessage');
    Route::post('/getUnreadUserCount', 'ChatController@getUnreadUserCount');
    
    
});
