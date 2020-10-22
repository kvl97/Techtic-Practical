<?php

Route::get('/clear-config', function() {
    $exitCode = Artisan::call('config:clear');
    return;
    // return what you want
});

Route::group(['middleware' => ['web', 'guest.admin:admin']], function() {
    Route::get('/', ['uses' => 'Backend\AuthenticateController@index']);
    Route::get('/login', ['as' => 'login', 'uses' => 'Backend\AuthenticateController@index']);
    Route::post('/login', [ 'uses' => 'Backend\AuthenticateController@loginValidate']);

    Route::post('/form-admin-forgot',[
        'as' => 'form-admin-forgot',
        'uses' => 'Backend\AuthenticateController@forgot_password'
    ]);
    Route::any('reset-password/{code}', ['as' => 'reset-password','uses' => 'Backend\AuthenticateController@reset_password']);
});

Route::group(['middleware' => ['web', 'auth.admin:admin']], function() {
    Route::get('/', 'Backend\AuthenticateController@dashboard')->name('dashboard');
    Route::get('/dashboard', 'Backend\AuthenticateController@dashboard')->name('dashboard');
    Route::any('/my-profile', 'Backend\AuthenticateController@myProfile')->name('myprofile');
    Route::get('/logout', 'Backend\AuthenticateController@logout')->name('logout');

    Route::any('/register-chart-data', 'Backend\AuthenticateController@registerChartData')->name('dashboard');



    Route::get('blog', 'Backend\BlogController@getIndex');
    Route::any('blog/add', 'Backend\BlogController@anyAdd');
    Route::post('blog/list-ajax', 'Backend\BlogController@anyListAjax');
    Route::any('blog/edit/{id}','Backend\BlogController@anyEdit');
    /* Route::any('blog/delete/{id}','Backend\BlogController@getDelete')->middleware('permission:6,i_delete'); */

    

    

    

   
    /* Route::any('location/view/{id}','Backend\LocationController@anyView')->middleware('permission:21,i_list'); */

   
});

