<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function () {
    Route::post('topics/{id}/votes', 'Api\TopicsVoteController@store');
    Route::resource('topics', 'Api\TopicsController');
});
