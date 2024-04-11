<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('building-case/highest-price', 'App\Http\Controllers\BuildingCaseController@getMostExpensiveForEachCases');
