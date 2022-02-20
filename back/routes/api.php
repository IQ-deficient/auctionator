<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CategoryConditionController;
use App\Http\Controllers\ItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user', [AuthController::class, 'userProfile']);
});

Route::group([
    'middleware' => 'auth'
], function () {
    // not sure how to protect api calls against unauthenticated users
    // update: This should do the trick, requires user being logged in and his JWT token to get access to this route
});

// Auctions
Route::post('/auctions', [AuctionController::class, 'store']);
Route::get('/auctions', [AuctionController::class, 'index']);
Route::get('/active_auctions', [AuctionController::class, 'getActive']);
Route::put('/auctions/{auction}', [AuctionController::class, 'update']);
Route::delete('/auctions/{auction}', [AuctionController::class, 'destroy']);
Route::get('/filtered_auctions', [AuctionController::class, 'getFiltered']);


// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/active_categories', [CategoryController::class, 'getActive']);

// Genders
Route::get('/genders', [GenderController::class, 'index']);

// Countries
Route::get('/countries', [CountryController::class, 'index']);

// CategoryCondition
Route::post('/category_conditions', [CategoryConditionController::class, 'getConditionsForCategory']);

// Items
Route::get('/items', [ItemController::class, 'index']);
