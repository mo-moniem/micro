<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\NoteController;


Route::apiResource('notes', NoteController::class)->middleware('auth.check');
