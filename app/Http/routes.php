<?php

use App\Http\Controllers\Api\TopicsController;

Route::get('/', ['middleware' => 'guest', function () {
    return view('welcome');
}]);

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/{vue_capture?}', function () {
            return view('home');
        })->where('vue_capture', '[\/\w\.-]*');
    });
});

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
    Route::post('topics/{id}/votes', 'TopicsVoteController@store');
    Route::patch('topics/{id}', 'TopicsController@patch');
    Route::resource('topics', 'TopicsController');

    Route::post('episodes/{id}/scheduled-topics', 'EpisodeScheduledTopicsController@store');
    Route::get('episodes/{id}/scheduled-topics', 'EpisodeScheduledTopicsController@index');
    Route::resource('episodes', 'EpisodesController');
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

