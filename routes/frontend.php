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

Route::prefix('')->namespace('Frontend')->group(function () {
	Route::post('/getMapMarker', 'PropertyController@getMapMarker');
	Route::post('/getFavMapMarker', 'PropertyController@getFavMapMarker');
	Route::post('/fetchAddress', 'PropertyController@fetchAddress');
	Route::group(['middleware' => ['CheckAgency']],function () {
		Route::get('/{slug}/login', 'UserController@index');
		Route::post('/{slug}/loginPost', 'UserController@loginPost');
		Route::get('/{slug}/signup', 'UserController@signup');
    	Route::post('/{slug}/sign-up', 'UserController@signupPost');
    	Route::get('/{slug}/email-verification', 'UserController@emailVerification');
        Route::post('/{slug}/signup-email-verification', 'UserController@postEmailVerification');
        Route::get('/{slug}/logout', 'UserController@logout');
        Route::get('/{slug}/forgotPass', 'UserController@forgotPass');
	    Route::post('/{slug}/forgotPasswordPost', 'UserController@forgotPasswordPost');
	    Route::get('/{slug}/otpverify', 'UserController@otpverify');
	    Route::post('/{slug}/otpverifyPost', 'UserController@otpverifyPost');
	    Route::group(['middleware' => ['IsCustomer']],function () {
		    Route::get('/{slug}/customer-my-account', 'MyaccountController@index');
		    Route::post('/{slug}/editMyProfile', 'MyaccountController@editMyProfile');
		    Route::post('/{slug}/changeEmail', 'MyaccountController@changeEmail');
		    Route::get('/{slug}/checkemail', 'MyaccountController@checkemail');
		    Route::post('/{slug}/changeEmailVeriy', 'MyaccountController@postEmailVerification');
		    Route::post('/{slug}/userChangePassword', 'MyaccountController@changePassword');
		    Route::post('/{slug}/deactive_account', 'MyaccountController@deactive_account');
		    Route::get('/{slug}/favorite-property-list', 'PropertyController@favoritePropertyList');
		    Route::get('/{slug}/customer-chat-list', 'ChatController@index');
		});

	    Route::get('/{slug}/changePassword', 'UserController@changePassword');
	    Route::post('/{slug}/changePasswordPost', 'UserController@changePasswordPost');
		Route::get('/{slug}/properties', 'PropertyController@propertiesList');
		Route::get('/{slug}/properties-search', 'PropertyController@properties_search');
		Route::get('/{slug}/favorite-properties-search', 'PropertyController@fav_properties_search');
		Route::get('/{slug}/property-detail/{address}-{listid}', 'PropertyController@propertyDetails')->name('property-detail');
		Route::post('/{slug}/fav-unfav-Property', 'PropertyController@favunfavProperty');
		Route::post('/{slug}/property-contact-form', 'PropertyController@propertyContactForm');
		Route::get('/{slug}/about-us', 'CMSController@aboutus');
	});
});