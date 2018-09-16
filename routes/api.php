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

Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('register', 'AuthenticateController@register');
Route::get('/account/type', 'AccountTypeController@index');
Route::group(['middleware' => 'auth:api'], function()
{
    Route::get('user', 'UserController@show');
    Route::group(
        [
            'middleware' => 'customer'
        ],
        function () {
            
            Route::get('user/account', 'UserController@getAccounts');
            Route::post('user/check/password', 'UserController@checkPassword');
            Route::post('user/profile/update', 'UserController@updateProfile');
            Route::post('user/password/update', 'UserController@updatePassword');
            Route::post('customer/request', 'CustomerController@requestAccount');
            Route::group(
                [
                    'prefix' => 'transaction'
                ],
                function () {
                    Route::post('deposit', 'TransactionController@deposit');
                    Route::post('withdraw', 'TransactionController@withdraw');
                    Route::post('transfer', 'TransactionController@transfer');
                    Route::post('account/check', 'TransactionController@checkAccount');
                    Route::post('mutation', 'TransactionController@mutation');
                   
                }
            );
        }
    );

    Route::group(
        [
            'middleware' => 'manager'
        ],
        function () {
            Route::get('bank/saldo', 'TransactionController@getSaldoBank');
            Route::get('bank/customer/total', 'CustomerController@totalCustomer');
        }
    );

    Route::group(
        [
            'middleware' => 'teller'
        ],
        function () {
            Route::get('bank/customer', 'CustomerController@getCustomer');
            Route::post('bank/account/find', 'CustomerController@findAccount');
            Route::get('bank/account/request', 'CustomerController@getAccountRequest');
            Route::post('bank/account/approve', 'CustomerController@approveAccount');
            Route::post('bank/account/block', 'CustomerController@blockAccount');
            Route::get('bank/account', 'CustomerController@account');
        }
    );
    
});