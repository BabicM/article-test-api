<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', [TestController::class, 'test'])->name('test');

Route::resource('article', ArticleController::class);
Route::put('article/{article}/category-sync', [ArticleController::class, 'categorySync'])->name('article.category-sync');
Route::resource('category', CategoryController::class);

Route::post('user/register',[UserController::class, 'register'])->name('user.register');
Route::post('user/login',[UserController::class, 'login'])->name('user.login');

Route::get('user/roles', [UserController::class, 'getAvailableRoles'])->name('user.get-available-roles');

Route::middleware('auth:sanctum')->group(function () {
   Route::get('user/me', [UserController::class, 'me'])->name('user.me');
});
