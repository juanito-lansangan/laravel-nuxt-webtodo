<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('testcookie', function() {
    return response('Test cookie')->cookie('test_cookie', 'value', 60, '/', null, false, true);
});

Route::get('test', function() {
    return "i'm still working";
});
