<?php

Route::group(['middleware' => 'AgentAuthCheck', 'namespace' => 'Agent'], function () {
    Route::get('/agent/home', 'UserController@index')->name('agent.home');
    Route::post('/leads-chart', 'UserController@ajaxLeadChart')->name('agent.lead.chart.data');
    Route::get('/agent/my-account', 'MyaccountController@index')->name('agent.my.account');
    Route::post('/agent/editMyProfile', 'MyaccountController@editMyProfile')->name('agent.edit.my.profile');
    Route::post('/agent/changeEmail', 'MyaccountController@changeEmail')->name('agent.change.email');
    Route::post('/agent/changeEmailVeriy', 'MyaccountController@postEmailVerification')->name('agent.change.email.verify');
    Route::post('/agent/changePassword', 'MyaccountController@changePassword')->name('agent.profile.change.password');

    Route::group(['prefix'=>'agent'],function(){
    Route::get('/property/property-list', 'PropertyController@index')->name('agent.property.list');
    Route::post('/property/property-list-datatable', 'PropertyController@datatableList')->name('agent.property.datatable.list');
    Route::get('/property/property-add', 'PropertyController@create')->name('agent.property.add');
    Route::post('/property/property-store', 'PropertyController@store')->name('agent.property.store');
    Route::post('/property/status/{id}', 'PropertyController@activeInactive')->name('agent.property.change.status');
    Route::get('/property/edit/{id}', 'PropertyController@edit')->name('agent.property.edit');
    Route::post('/property/update/{id}', 'PropertyController@update')->name('agent.property.update');
    Route::post('/property/delete/image', 'PropertyController@removeImage')->name('agent.property.delete.image');
    Route::post('/property/delete/video', 'PropertyController@removeVideo')->name('agent.property.delete.video');
    Route::post('/property/delete', 'PropertyController@propertyDelete')->name('agent.property.delete');
    Route::get('/leads', 'LeadsController@index')->name('agent.leads.list');
    Route::get('/chat-list', 'ChatController@index')->name('agent.chat.list');
    
    //Agent Send mail
     Route::get('/sendmail','SendmailController@index')->name('agent.send.mail');
     Route::post('/sendmail/customer','SendmailController@getCustomerList')->name('agent.send.mail.customer.list.ajax');
     Route::post('/sendmail/property','SendmailController@getPropertyList')->name('agent.send.mail.property.list.ajax');
     Route::post('/sendmail/general','SendmailController@sendGeneralEmail')->name('agent.send.general.email');
     
    Route::prefix('notifications')->group(function () {
                Route::get('/', 'NotificationController@index')->name('notification.listing.agent');
                Route::post('/notificationAjax', 'NotificationController@notificationAjax');
                Route::get('/{id}/delete', 'NotificationController@deleteNotification');
            });
            Route::get('readnotification', 'NotificationController@readnotification');
    });
});

