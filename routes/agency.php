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

Route::prefix('agency')->namespace('Agency')->group(function () {
	Route::get('/', 'UserController@index');
    Route::get('/login', 'UserController@index')->name('agency.login.page');
    Route::post('/login', 'UserController@loginPost')->name('agency.login');
    Route::get('/logout', 'UserController@logout');
    Route::get('/forgotPass', 'UserController@forgotPass');
    Route::post('/forgotPasswordPost', 'UserController@forgotPasswordPost');
    Route::get('/otpverify', 'UserController@otpverify');
    Route::post('/otpverifyPost', 'UserController@otpverifyPost');

    Route::get('/changePassword/{id}', 'UserController@changePassword')->name('agency.change.password');
    Route::post('/changePasswordPost', 'UserController@changePasswordPost');
    Route::get('/signup', 'UserController@signup')->name('agency.signup');
    Route::post('/sign-up', 'UserController@signupPost')->name('agency.signup.post');
     Route::post('/resend-otp', 'UserController@resendOTP')->name('signup.resend_otp');
     Route::get('/checkslug', 'UserController@checkUniqueSlug');
        Route::group(['middleware' => ['IsAgency']],function () {
            Route::prefix('notifications')->group(function () {
                Route::get('/', 'NotificationController@index')->name('notification-listing');
                Route::post('/notificationAjax', 'NotificationController@notificationAjax');
                Route::get('/{id}/delete', 'NotificationController@deleteNotification');
            });
            Route::get('readnotification', 'NotificationController@readnotification');
            Route::get('/email-verification', 'UserController@emailVerification')->name('email.verification');
            Route::post('/signup-email-verification', 'UserController@postEmailVerification')->name('signup.email_verification');

            Route::get('/subscription-plans', 'UserController@subscriptionPlans')->name('subscription.plans');
            Route::post('/post-subscription-plan', 'UserController@postSubscriptionPlan')->name('signup.post.subscription.plan');

            Route::get('/payment-details', 'UserController@paymentDetail')->name('subscription.payment.detail');
            Route::post('/create-setup-intent', 'UserController@createSetupIntent')->name('stripe.setupintent');
            Route::post('/store-subscriptions', 'UserController@storeSubscription')->name('stripe.store.subscription');
            Route::get('/complete-agency-signup', 'UserController@completeAgencySignup')->name('complete.signup');
            Route::get('/signup-agency-dashboard', 'UserController@signupDashboard')->name('signup.agency.dashboard');
            Route::group(['middleware' => ['IsAgencyProfileSetup']],function () {

                Route::prefix('subscription')->group(function () {
                    Route::get('/test', 'SubscriptionController@test');
                    Route::get('/', 'SubscriptionController@index')->name('agency.subscription');
                    Route::get('/transactions', 'SubscriptionController@transaction')->name('agency.subscription.transaction');
                    Route::get('/payment-setting', 'SubscriptionController@paymentSetting')->name('agency.payment.setting');
                    Route::post('/transactionsAjax', 'SubscriptionController@transactionAjax');
                    Route::post('/upgrade-subscription', 'SubscriptionController@upgradeSubscription');
                    Route::post('/active-subscription', 'SubscriptionController@activeSubscription');
//                    Route::post('/downgrade-subscription', 'SubscriptionController@downgradeSubscription');
                    Route::post('/cancel-subscription', 'SubscriptionController@cancelSubscription');
                    Route::post('/add-card', 'SubscriptionController@addCardModal')->name('agency.card.modal');
                    Route::post('/store-card', 'SubscriptionController@storeCard')->name('stripe.store');
                    Route::post('/delete-card', 'SubscriptionController@deleteCard')->name('stripe.delete');
                });
                
                Route::get('/my-account', 'MyaccountController@index');
                Route::post('/editMyProfilePost', 'MyaccountController@editMyProfilePost');
                Route::post('/changePassword', 'MyaccountController@changePassword');
                Route::post('/deactive_account', 'MyaccountController@deactive_account');

                Route::group(['middleware' => ['IsActiveSubscription']],function () {
                    Route::get('/home', 'DashboardController@home')->name('agency.home');
                    Route::post('/leads-chart', 'DashboardController@ajaxLeadsChart')->name('agency.leads.chart.data');
                    Route::post('/customer-chart', 'DashboardController@ajaxCustomerChart')->name('agency.customer.chart.data');
                    Route::get('/agent', 'AgentController@index');
                    Route::get('/agent/add', 'AgentController@addAgent');
                    Route::post('/agent/addAgentPost', 'AgentController@addAgentPost');
                    Route::post('/agent/editAgentPost', 'AgentController@editAgentPost');
                    Route::get('/agent/edit/{id}', 'AgentController@editAgent');
                    Route::post('/agent/listAgentAjax', 'AgentController@listAgentAjax');
                    Route::post('/agent/delete/{id}', 'AgentController@delete');
                    Route::post('/agent/activeInactive/{id}', 'AgentController@activeInactive');
                    /*Route::post('/agent/delete/{id}', 'AgentController@userStatus');*/
                    Route::get('/agent/view/{id}', 'AgentController@show')->name('agency.agent.view');
                    Route::get('/agent/leads/{id}', 'LeadsController@agentLeads')->name('agency.agent.leads');
                    Route::get('/agent/chat-list/{id}', 'ChatController@chatList')->name('agency.agent.chat');
                    Route::get('/chat-list', 'ChatController@agencyChatList')->name('agency.chat');
                    Route::post('/agent/view/property', 'AgentController@getAgentPropertyList')->name('agency.agent.view.property.list');
                    
                    Route::get('/checkemail', 'MyaccountController@checkemail');
                    Route::post('/changeEmail', 'MyaccountController@changeEmail');
                    Route::post('/changeEmailVeriy', 'MyaccountController@postEmailVerification');
                    Route::post('/agent/changeEmail', 'AgentController@changeEmail');
                    Route::get('/settings', 'SettingController@generalSettings');
                    Route::post('/saveAboutAgency', 'SettingController@saveAboutAgency');
                    Route::post('/saveDesignSetting', 'SettingController@saveDesignSetting');
                    Route::post('/saveContactFormSettings', 'SettingController@saveContactFormSettings');
                    Route::post('/saveSEOSettings', 'SettingController@saveSEOSettings');
                    
                    Route::get('/property-list','PropertyController@index')->name('agency.property.list');
                    Route::post('/property-list-datatable','PropertyController@datatableList')->name('agency.property.datatable.list');
                    Route::get('/property-add','PropertyController@create')->name('agency.property.add');
                    Route::post('/property-store','PropertyController@store')->name('agency.property.store');
                    Route::post('/status/{id}', 'PropertyController@activeInactive')->name('agency.property.change.status');
                    Route::get('/property/edit/{id}', 'PropertyController@edit')->name('agency.property.edit');
                    Route::post('/property/update/{id}', 'PropertyController@update')->name('agency.property.update');
                    Route::post('/property/delete/image', 'PropertyController@removeImage')->name('agency.property.delete.image');
                    Route::post('/property/delete/video', 'PropertyController@removeVideo')->name('agency.property.delete.video');
                    Route::post('/property/delete', 'PropertyController@propertyDelete')->name('agency.property.delete');
                    Route::get('/leads', 'LeadsController@index');
                    Route::post('/leads/listLeadsAjax', 'LeadsController@listLeadsAjax');
                    Route::post('/leads/addNotes', 'LeadsController@addNotes');
                    Route::post('/leads/delete/{id}', 'LeadsController@deleteLeads');
                    Route::post('/leads/changeStatus', 'LeadsController@changeStatus');
                    
                    //Agency customer
                     Route::get('/customer-list','CustomerController@index')->name('agency.customer.list');
                     Route::post('/customer/ajax/list','CustomerController@customerAjaxList')->name('agency.customer.list.ajax');
                     Route::get('/customer/edit/{id}','CustomerController@edit')->name('agency.customer.edit');
                     Route::post('/customer/update','CustomerController@update')->name('agency.customer.update');
                     Route::get('/customer/view/{id}','CustomerController@view')->name('agency.customer.view');
                     Route::get('/customer/view/fav/{id}','CustomerController@viewFavouriteProperty')->name('agency.customer.view.fav.property');
                     Route::post('/customer/delete/{id}','CustomerController@destroy')->name('agency.customer.delete');
                     Route::get('/customer/checkemail', 'CustomerController@checkCustomerEmail')->name('agency.customer.check.email');
                     Route::post('/customer/changeemail', 'CustomerController@changeCustomerMail')->name('agency.customer.change.mail');
                     Route::post('/changeEmailVeriy', 'CustomerController@postEmailVerification')->name('agency.customer.change.email.verify');
                     Route::post('/customer/changestatus/{id}', 'CustomerController@changeCustomerStatus')->name('agency.customer.change.status');
                     Route::post('/customer/fav/property', 'CustomerController@getCustomerFavouriteProperty')->name('agency.customer.fav.property.list');
                     Route::post('/customer/unfav/property', 'CustomerController@unfavouriteProperty')->name('agency.customer.property.unfav');
                     
                     //Agency Send mail
                     Route::get('/sendmail','SendmailController@index')->name('agency.send.mail');
                     Route::post('/sendmail/customer','SendmailController@getCustomerList')->name('agency.send.mail.customer.list.ajax');
                     Route::post('/sendmail/property','SendmailController@getPropertyList')->name('agency.send.mail.property.list.ajax');
                     Route::post('/sendmail/general','SendmailController@sendGeneralEmail')->name('agency.send.general.email');
                    
                });
            });
            //Route::post('/agent/addAgentPost', 'AgentController@addAgentPost');
    });
});
