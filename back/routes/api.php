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
use App\Http\Controllers\BidController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\WarehouseController;

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

// Authentication Middleware Group
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

// Middleware Group for Routes that require User to be Authenticated
Route::group([
    'middleware' => 'auth:api'
], function () {
    // not sure how to protect api calls against unauthenticated users
    // update: This should do the trick, requires user being logged in and his JWT token to get access to this route
});
//  TODO: appropriate middleware for routes (some must require being authenticated)

// Auctions
Route::post('/auctions', [AuctionController::class, 'store']);
Route::get('/auctions', [AuctionController::class, 'index']);
Route::get('/active_auctions', [AuctionController::class, 'getActive']);
Route::get('/auction_by_id', [AuctionController::class, 'getById']);
Route::put('/auctions/{auction}', [AuctionController::class, 'update']);
Route::delete('/auctions/{auction}', [AuctionController::class, 'destroy']);
Route::get('/filtered_auctions', [AuctionController::class, 'getFiltered']);

// Bids
Route::get('/bids', [BidController::class, 'index']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/active_categories', [CategoryController::class, 'getActive']);

// CategoryCondition
Route::post('/category_conditions', [CategoryConditionController::class, 'getConditionsForCategory']);

// Conditions
Route::get('/conditions', [ConditionController::class, 'index']);

// Countries
Route::get('/countries', [CountryController::class, 'index']);

// Genders
Route::get('/genders', [GenderController::class, 'index']);

// Histories
Route::get('/histories', [HistoryController::class, 'index']);

// Images
Route::get('/images', [ImageController::class, 'index']);

// Items
Route::get('/items', [ItemController::class, 'index']);

// Roles
Route::get('/roles', [RoleController::class, 'index']);

// Statuses
Route::get('/statuses', [StatusController::class, 'index']);

// Users (Manager & Admin)
Route::get('/users', [AuthController::class, 'index']);
Route::put('/users/{user}', [AuthController::class, 'update']);
Route::delete('/users/{user}', [AuthController::class, 'destroy']);

// UserRoles
Route::get('/user_roles', [UserRoleController::class, 'index']);

// Warehouses (Admin)
Route::post('/warehouses', [WarehouseController::class, 'store']);
Route::get('/warehouses', [WarehouseController::class, 'index']);
Route::get('/active_warehouses', [WarehouseController::class, 'getActive']);
Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update']);
Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy']);







