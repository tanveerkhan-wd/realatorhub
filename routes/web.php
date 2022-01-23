<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::post('ckeditor/upload', 'Admin\BlogController@uploadImage')->name('ckeditor.upload');
Route::prefix('admin')->namespace('Admin')->group(function () {
    Route::get('/', 'UserController@index');
    Route::get('/login', 'UserController@index');
    Route::post('/login', 'UserController@loginPost')->name('admin.login');
    Route::get('/logout', 'UserController@logout');

    Route::get('/forgotPass', 'UserController@forgotPass');
    Route::post('/forgotPasswordPost', 'UserController@forgotPasswordPost');

    Route::get('/otpverify', 'UserController@otpverify');
    Route::post('/otpverifyPost', 'UserController@otpverifyPost');

    Route::get('/changePassword', 'UserController@changePassword');
    Route::post('/changePasswordPost', 'UserController@changePasswordPost');


    Route::group(['middleware' => ['IsAdmin']], function () {
        Route::get('/home', 'DashboardController@home')->name('admin.home');
        Route::post('/revenue-chart', 'DashboardController@ajaxRevenueChart')->name('admin.revenue.chart.data');
        Route::post('/agency-chart', 'DashboardController@ajaxAgencyChart')->name('admin.agency.chart.data');

        Route::get('/profile', 'UserController@profile');
        Route::post('/profile', 'UserController@profilePost');

        Route::post('/change-profile', 'UserController@changeProfile');
        Route::post('/change-password', 'UserController@changePasswordPostAdmin');

        Route::prefix('notifications')->group(function () {
            Route::get('/', 'NotificationController@index')->name('notification-listing');
            Route::post('/notificationAjax', 'NotificationController@notificationAjax');
            Route::get('/{id}/delete', 'NotificationController@deleteNotification');
        });
        Route::get('readnotification', 'NotificationController@readnotification');

        Route::prefix('subscriptions')->group(function () {
            Route::get('/', 'SubscriptionController@index')->name('plans');
            Route::get('/{id}/show', 'SubscriptionController@showPlan')->name('view.plans');
            Route::post('/{id}/delete', 'SubscriptionController@deletePlan')->name('delete.plans');
            Route::get('/add', 'SubscriptionController@subscription')->name('add.new.subscription');
            Route::post('/store', 'SubscriptionController@storePlan')->name('store.plan');
            Route::post('/listAjax', 'SubscriptionController@listPlansAjax')->name('list.plan');
            Route::post('/listTransactionAjax', 'SubscriptionController@listTransactionAjax')->name('list.transaction');
            Route::get('/transaction', 'SubscriptionController@transactions')->name('transaction');
        });

        Route::prefix('agency')->group(function () {
            Route::get('/list', 'AgencyController@index')->name('admin.agency.list');
            Route::post('/datatable-list', 'AgencyController@datatableList')->name('admin.agency.datatable.list');
            Route::get('/edit/{id}', 'AgencyController@edit')->name('admin.agency.edit');
            Route::post('/status/{id}', 'AgencyController@activeInactive')->name('admin.agency.change.status');
            Route::post('/delete/{id}', 'AgencyController@deleteAgency')->name('admin.agency.delete');
            Route::post('/changeEmail', 'AgencyController@changeEmail')->name('admin.agency.change.email');
            Route::get('/checkemail', 'AgencyController@checkemail')->name('admin.agency.check.email');
            Route::post('/changeEmailVeriy', 'AgencyController@postEmailVerification')->name('admin.agency.change.email.verify');
            Route::post('/editMyProfilePost', 'AgencyController@editMyProfilePost')->name('admin.agency.edit.profile');
            Route::post('/checkslug', 'AgencyController@checkUniqueSlug')->name('admin.agency.check.unique.slug');
            Route::get('/view/{id}', 'AgencyController@show')->name('admin.agency.view');
            Route::get('/view/property/{id}', 'AgencyController@viewAgencyProperty')->name('admin.agency.view.property');
            Route::get('/view/subscription-plan/{id}', 'AgencyController@viewAgencySubscriptionPlan')->name('admin.agency.view.subscription.plan');
            Route::get('/view/transaction/{id}', 'AgencyController@viewAgencyTransaction')->name('admin.agency.view.transaction');
            Route::get('/view/agent/{id}', 'AgencyController@viewAgencyAgent')->name('admin.agency.view.agent');
            Route::get('/view/customer/{id}', 'AgencyController@viewAgencyCustomer')->name('admin.agency.view.customer');
            Route::post('/property-list', 'AgencyController@getAgencyProperty')->name('admin.agency.property.list');
            //Route::post('/subscription-plan', 'AgencyController@getAgencySubscriptionPlan')->name('admin.agency.subscription.plan');
            Route::post('/transaction-list', 'AgencyController@getAgencyTransaction')->name('admin.agency.transaction.list');
            Route::post('/agent-list', 'AgencyController@getAgencyAgent')->name('admin.agency.agent.list');
            Route::post('/customer-list', 'AgencyController@getAgencyCustomer')->name('admin.agency.customer.list');
            Route::post('/property/changestatus/{id}', 'AgencyController@changePropertyStatus')->name('admin.agency.property.change.status');
            Route::post('/agent/changestatus/{id}', 'AgencyController@changeAgentStatus')->name('admin.agency.agent.change.status');
            Route::post('/customer/changestatus/{id}', 'AgencyController@changeCustomerStatus')->name('admin.agency.customer.change.status');
            Route::get('/agent/edit/{id}', 'AgencyController@editAgent')->name('admin.agency.agent.edit');
            Route::get('/customer/edit/{id}/{agencyid}', 'AgencyController@editCustomer')->name('admin.agency.customer.edit');
            Route::post('/agent/update', 'AgencyController@updateAgentProfile')->name('admin.agency.agent.update');
            Route::post('/customer/update', 'AgencyController@updateCustomerProfile')->name('admin.agency.customer.update');
            Route::post('/agent/changeemail', 'AgencyController@changeAgentMail')->name('admin.agency.agent.change.mail');
            Route::get('/customer/checkemail', 'AgencyController@checkCustomerEmail')->name('admin.agency.customer.check.email');
            Route::post('/customer/changeemail', 'AgencyController@changeCustomerMail')->name('admin.agency.customer.change.mail');
            Route::post('/agent/delete/{id}', 'AgencyController@agentDelete')->name('admin.agency.agent.delete');
            Route::post('/customer/delete/{id}', 'AgencyController@customerDelete')->name('admin.agency.customer.delete');
        });


        Route::prefix('general-settings')->group(function () {
            Route::get('/', 'GeneralSettingController@index');
            Route::post('/saveLogoAndIconData', 'GeneralSettingController@saveLogoAndVideoData')->name('save-logo-and-icon');
            Route::post('/storeSeoSetting', 'GeneralSettingController@storeSeoSetting')->name('save-seo-settings');
            Route::post('/storeSmtpSettings', 'GeneralSettingController@storeSmtpSettings')->name('store.smtp.settings');
            Route::post('/storeDesignSetting', 'GeneralSettingController@storeDesignSetting')->name('store.design.setting');
        });

        Route::prefix('settings')->group(function () {  
            //Home melnu setting//
            Route::get('/', 'HomeController@index');    
            Route::post('/add-logo-banner', 'HomeController@addLogobanner');
            Route::post('/add-why-realtor-hubs', 'HomeController@addWhyRealtorHubs');   
            Route::post('/add-about', 'HomeController@addAbout');
            Route::post('/add-seosetting', 'HomeController@addSEOSetting');

            /*Route::prefix('aboutus-setting')->group(function () {   
                Route::get('/', 'AboutUsController@index');     
                Route::post('/add-about', 'AboutUsController@addAbout');
                Route::get('/why-us', 'AboutUsController@whyus');
                Route::post('/add-why-us', 'AboutUsController@addWhyUs');
                Route::get('/meta-section', 'AboutUsController@metaSection');
                Route::post('/add-meta-section', 'AboutUsController@addMetaSection');
            });*/

            /*Route::prefix('contactus-setting')->group(function () { 
                Route::get('/', 'ContactUsController@index');       
                Route::post('/add-genral-setting', 'ContactUsController@addGenralSetting');
                Route::get('/meta-section', 'ContactUsController@metaSection');
                Route::post('/add-meta-section', 'ContactUsController@addMetaSection');
            });

            Route::prefix('faq-setting')->group(function () {   
                Route::get('/', 'FaqController@index');     
                Route::post('/add-genral-setting', 'FaqController@addGenralSetting');
                Route::get('/meta-section', 'FaqController@metaSection');
                Route::post('/add-meta-section', 'FaqController@addMetaSection');
            });*/
        });

        //About us setting

        Route::get('/about-us-setting', 'AboutusSettingController@aboutUsSetting');
        Route::post('/saveaboutBelieve', 'AboutusSettingController@saveaboutBelieve');
        Route::post('/saveaboutMission', 'AboutusSettingController@saveaboutMission');      
        Route::post('/aboutsavemetaSetting', 'AboutusSettingController@aboutsavemetaSetting');

        //Contact us setting

        Route::get('/contact-us-setting', 'ContactusSettingController@contactUsSetting');
        Route::post('/saveContactUs', 'ContactusSettingController@saveContactUs');    
        Route::post('/saveContactMetaSetting', 'ContactusSettingController@saveContactMetaSetting');

        Route::prefix('blog-post-category')->group(function () {    
            Route::get('/', 'BlogCategoryController@blogcategoryList');     
            Route::post('/blogcategoryAjax', 'BlogCategoryController@blogcategoryAjax');
            Route::get('/add', 'BlogCategoryController@addblogcategory');
            Route::post('/add', 'BlogCategoryController@addblogcategoryPost');
            Route::get('/{id}/edit', 'BlogCategoryController@editblogcategory');
            Route::post('/edit/{id}', 'BlogCategoryController@editblogcategoryPost');
            Route::post('/delete/{id}', 'BlogCategoryController@deleteblogcategoryPost');
            Route::post('/activeInactive/{id}', 'BlogCategoryController@activeInactive');
        });

        Route::prefix('cms')->group(function () {   
            Route::get('/', 'CMSController@cmsList');       
            Route::post('/cmsAjax', 'CMSController@cmsAjax');
            Route::get('/add', 'CMSController@addCMS');
            Route::post('/add', 'CMSController@addCMSPost');
            Route::get('/{id}/edit', 'CMSController@editCMS');
            Route::post('/edit/{id}', 'CMSController@editCMSPost');
            Route::post('/delete/{id}', 'CMSController@deleteCMSPost');
            Route::post('/activeInactive/{id}', 'CMSController@activeInactive');
        });


        Route::prefix('faqs')->group(function () {  
            Route::get('/', 'FAQController@faqList');       
            Route::post('/faqAjax', 'FAQController@faqAjax');
            Route::get('/add', 'FAQController@addfaq');
            Route::post('/add', 'FAQController@addfaqPost');
            Route::get('/{id}/edit', 'FAQController@editfaq');
            Route::post('/edit/{id}', 'FAQController@editfaqPost');
            Route::post('/delete/{id}', 'FAQController@deletefaqPost');
            Route::post('/activeInactive/{id}', 'FAQController@activeInactive');
            Route::post('/changeSortOrder', 'FAQController@changeSortOrder');
        });
        Route::get('/faq-setting{id?}', 'FAQController@faqSetting');
        Route::post('/savefaqsetting', 'FAQController@savefaqsetting');

        Route::get('/blog-setting{id?}', 'BlogController@blogSetting');
        Route::post('/saveBlogSetting', 'BlogController@saveBlogSetting');

        Route::prefix('blog-post')->group(function () { 
            Route::get('/', 'BlogController@blogpostList');     
            Route::post('/blogpostAjax', 'BlogController@blogpostAjax');
            Route::get('/add', 'BlogController@addblogpost');
            Route::post('/add', 'BlogController@addblogpostPost');
            Route::get('/{id}/edit', 'BlogController@editblogpost');
            Route::post('/edit/{id}', 'BlogController@editblogpostPost');
            Route::post('/delete/{id}', 'BlogController@deleteblogpostPost');
            Route::post('/activeInactive/{id}', 'BlogController@activeInactive');
        });

        Route::prefix('transactions')->group(function () {
            Route::get('/', 'TransactionController@index')->name('admin.transactions');
            Route::post('/listTransactionAjax', 'TransactionController@listTransactionAjax')->name('ajax.admin.transactions');
        });

        Route::prefix('email-template')->group(function () {
            Route::get('/', 'EmailTemplateController@index');
            Route::post('/emailAjax', 'EmailTemplateController@emailAjax');
            Route::get('/{id}/edit', 'EmailTemplateController@editemail');
            Route::post('/edit/{id}', 'EmailTemplateController@editemailPost');
        });

        Route::prefix('property')->group(function() {
            Route::get('/list', 'PropertyController@index')->name('admin.property.list');
            Route::post('/datatable-list', 'PropertyController@datatableList')->name('admin.property.datatable.list');
            Route::post('/status/{id}', 'PropertyController@activeInactive')->name('admin.property.change.status');
            Route::get('/edit/{id}/{aid}', 'PropertyController@edit')->name('admin.property.edit');
            Route::post('/update/{id}', 'PropertyController@update')->name('admin.property.update');
            Route::post('/delete/image', 'PropertyController@removeImage')->name('admin.property.delete.image');
            Route::post('/delete/video', 'PropertyController@removeVideo')->name('admin.property.delete.video');
            Route::post('/delete', 'PropertyController@propertyDelete')->name('admin.property.delete');
        });

    });
});
require('agency.php');
require('frontend.php');
require('common.php');
