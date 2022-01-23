<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::group(['namespace' => 'Frontend'], function () {
     Route::get('/', 'HomeController@index')->name('user.home');
     Route::get('/about-us', 'HomeController@aboutUs')->name('user.about.us');
     Route::get('/contact-us', 'HomeController@contactUs')->name('user.contact.us');
     Route::post('/contact-us-save', 'HomeController@addContactUS')->name('user.contact.us.store');
     Route::get('/terms-condition', 'HomeController@termsCondition')->name('user.terms.condition');
     Route::get('/faqs', 'HomeController@getFaqs')->name('user.faqs');
     Route::get('/privacy-policy', 'HomeController@privacyPolicy')->name('user.privacy.policy');
     
     Route::get('/blogs', 'BlogController@index')->name('user.blog.home.list');
     Route::post('/blog-list', 'BlogController@getBlogList')->name('user.blog.list');
     Route::get('/blog-detail/{slug}', 'BlogController@blogDetail')->name('user.blog.detail');
     Route::post('/get-loadmorelist', 'BlogController@getloadmoreList')->name('user.blog.load.more');
     
});