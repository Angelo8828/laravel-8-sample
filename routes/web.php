<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Admin\LessonsController as AdminLessonsController;
use App\Http\Controllers\Users\LessonsController as UsersLessonsController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::group([
    'middleware' => ['auth'],
    'prefix' => 'admin'
], function () {
    Route::get('/', [AdminLessonsController::class, 'index']);
    Route::post('/', [AdminLessonsController::class, 'store']);
});

Route::group([
    'middleware' => ['auth'],
], function () {
    Route::get('lessons', [UsersLessonsController::class, 'index'])->name('user::view-lessons');
    Route::get('lessons/{lessonId}', [UsersLessonsController::class, 'get'])->name('user::view-lesson');
    Route::post('lessons/{lessonId}/comments', [UsersLessonsController::class, 'postComment'])->name('user::post-comment');
});

require __DIR__.'/auth.php';
