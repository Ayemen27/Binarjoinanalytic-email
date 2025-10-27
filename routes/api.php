<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('api.enabled')->group(function () {

    Route::get('/domains/{key}/{type}', "ApiController@getDomains"); // Fetch domains with filters
    Route::post('/emails/{apiKey}', "ApiController@create"); // Create a new email
    Route::post('/emails/{apiKey}/{email}/{username}/{domain}', "ApiController@updateEmail"); // Update email with username/domain
    Route::post('/emails/{apiKey}/{email}', "ApiController@deleteEmail"); // Delete an email
    Route::post('/messages/{apiKey}/message/{messageId}', "ApiController@deleteMessage"); // Delete a message
    Route::get('/messages/{apiKey}/{email}', "ApiController@getMessages"); // Get all messages
    Route::get('/messages/{apiKey}/message/{messageId}', "ApiController@getMessage"); // Get a specific message
    Route::get('/d/{hash_id}/{file?}', "ApiController@download"); // Get a specific message

});
