<?php

use App\Http\Controllers\API\QuestionCategoryController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\RandomQuestionController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Question API
Route::prefix('question')->name('question.')->group(function () {
    Route::get('', [QuestionController::class, 'fetch'])->name('fetch');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('', [QuestionController::class, 'create'])->name('create');
        Route::patch('{id}', [QuestionController::class, 'update'])->name('update');
        Route::delete('{id}', [QuestionController::class, 'destroy'])->name('destroy');
    });
});

// Random Question API
Route::prefix('random-question')->name('random-question')->group(function () {
    Route::get('', [RandomQuestionController::class, 'randomQuestion'])->name('random-question');
});

// Category Question
Route::prefix('category-question')->name('category-question')->group(function () {
    Route::get('', [QuestionCategoryController::class, 'fetch'])->name('category-question');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('', [QuestionCategoryController::class, 'create'])->name('create');
        Route::post('update/{id}', [QuestionCategoryController::class, 'update'])->name('update');
        Route::delete('{id}', [QuestionCategoryController::class, 'destroy'])->name('destory');
    });
});

// Auth API
Route::name('auth.')->group(function () {
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::post('register', [UserController::class, 'register'])->name('register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('user', [UserController::class, 'fetch'])->name('fetch');
    });
});
