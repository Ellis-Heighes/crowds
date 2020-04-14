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

Route::get('/privacy', function() {
    return view('privacy');
});

Auth::routes(['reset' => false]);

Route::post('/facebook/login', 'FacebookLoginController@login');

Route::middleware(['auth'])->group(function () {

    Route::get('/feed', 'OrdersFeedController@index');

    Route::post('/setup/step-one', 'UserController@completeSetupStepOne');
    Route::patch('/setup/step-two', 'UserController@completeSetupStepTwo'); // post because we also use it for Facebook login in non-auth pages
    Route::patch('/setup/step-three', 'UserController@completeSetupStepThree');
    Route::patch('/setup/step-four', 'UserController@completeSetupStepFour');
    Route::patch('/setup/step-five', 'UserController@completeSetupStepFive');

    Route::patch('/setup/back', 'UserController@previousSetupStep');

    Route::get('/province/{province}', 'ProvinceController@show');
    Route::get('/city/{city}', 'CityController@show');
});

Route::middleware(['auth', 'setup.complete'])->group(function () {

    Route::get('/user/{user}', 'UserController@show')->middleware('only.contactable-users');
    Route::get('/user/{user}/reputation', 'UserReputationController@show');

    Route::post('/order/create', 'OrdersController@create')->middleware('limit.order');

    Route::post('/bid/create', 'BidController@create')->middleware('limit.bid');

    Route::get('/orders', 'OrdersController@index');
    Route::delete('/order/{order}/delete', 'OrdersController@delete')->middleware('is.order-owner');

    Route::patch('/bid/{bid}/accept', 'BidController@accept');

    Route::patch('/bid/{bid}/no_show', 'BidController@noShow');
    Route::patch('/bid/{bid}/fulfilled', 'BidController@fulfill');

    Route::get('/bids', 'BidController@index');

    Route::patch('/bid/{bid}/cancel', 'BidController@cancel');

    Route::get('/notifications', 'NotificationsController@index');
    Route::patch('/notifications', 'NotificationsController@update');

    Route::get('/account', 'AccountSettingsController@edit');
    Route::patch('/account/password', 'AccountSettingsController@updatePassword');
    Route::patch('/account/contact', 'AccountSettingsController@updateContact');
    Route::patch('/account/notifications', 'AccountSettingsController@updateNotifications');
    Route::delete('/account/delete', 'AccountSettingsController@deleteAccount');
    Route::patch('/account/services', 'AccountSettingsController@updateServices');

    Route::get('/barangay', 'BarangayOrdersController@index');
    Route::post('/barangay-order/create', 'BarangayOrdersController@create')->middleware(['limit.order', 'only.non-officers']);

    Route::patch('/barangay-order/fulfilled', 'BarangayOrdersController@fulfill');

    Route::post('/officer-account/request', 'OfficerAccountController@requestAccount');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/get-started', function() {
        return view('get-started');
    });
});

