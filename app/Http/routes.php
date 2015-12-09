<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', function () {
        return view('home');
    });
});

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
    Route::post('topics/{id}/votes', 'TopicsVoteController@store');
    Route::patch('topics/{id}', 'TopicsController@patch');
    Route::resource('topics', 'TopicsController');
});

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::group(['prefix' => 'github'], function () {
        Route::get('/', 'GitHubOAuthController@redirectToProvider');
        Route::get('callback', 'GitHubOAuthController@handleProviderCallback');
    });
    Route::group(['prefix' => 'twitter'], function () {
        Route::get('/', 'TwitterOAuthController@redirectToProvider');
        Route::get('callback', 'TwitterOAuthController@handleProviderCallback');
    });
});

Route::get('logout', 'Auth\AuthController@getLogout');

