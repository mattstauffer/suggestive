<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function () {
    Route::post('topics/{id}/votes', 'Api\TopicsVoteController@store');
    Route::patch('topics/{id}', 'Api\TopicsController@patch');
    Route::resource('topics', 'Api\TopicsController');
});
