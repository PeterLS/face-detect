<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\PersonDescriptorsController;
use App\Http\Controllers\PersonsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PagesController::class, 'index']);
Route::resource('persons', PersonsController::class)->names('persons')->except('show');
Route::resource('persons/{person}/descriptors', PersonDescriptorsController::class)->names('persons.descriptors')
    ->except('edit', 'update');
