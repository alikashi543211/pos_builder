<?php

use App\Http\Controllers\API\ResumeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/save-resume-path', [ResumeController::class, 'saveResumePath']);
