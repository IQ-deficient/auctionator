<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\RoleController;
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

// Authentication
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->middleware('logged_in');
    Route::post('/register', [AuthController::class, 'register'])->middleware('logged_in');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user', [AuthController::class, 'userProfile']);
    Route::get('/image', [AuthController::class, 'userImage']);
});

Route::post('/filtered_auctions', [AuctionController::class, 'getFiltered']);
Route::get('/menu_categories', [CategoryController::class, 'getMenuCategories']);
Route::get('/active_countries', [CountryController::class, 'getActive']);
Route::post('/contact_us', [UserController::class, 'sendMail']);

Route::group([
    'middleware' => ['auth:api', 'check_user']
], function () {

    // Auctions
    Route::post('/auction', [AuctionController::class, 'store']);
    Route::get('/auctions', [AuctionController::class, 'index']);
    Route::get('/auction/{auction}', [AuctionController::class, 'show']);
    Route::put('/auction/{auction}', [AuctionController::class, 'update']);
    Route::delete('/auction/{auction}', [AuctionController::class, 'destroy']);
    Route::delete('/auction_soft/{auction}', [AuctionController::class, 'softDestroyAndRestore']);

    // Bids
    Route::post('/bid', [BidController::class, 'store']);
    Route::get('/user_bids', [BidController::class, 'getBidsForUser']);

    // Categories
    Route::get('/parent_categories', [CategoryController::class, 'getParentCategories']);
    Route::get('/category/{category}', [CategoryController::class, 'show']);
    Route::post('/child_categories_conditions', [CategoryController::class, 'getChildCategoriesAndConditions']);

    // Genders
    Route::get('/genders', [GenderController::class, 'index']);

    // Histories
    Route::post('/history', [HistoryController::class, 'store']);
    Route::get('/user_histories', [HistoryController::class, 'getHistoriesForUser']);


    // Items
    Route::post('/images/{item}', [ItemController::class, 'addItemImage']);

    // Roles
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/auth_roles', [RoleController::class, 'getAuthUserRoles']);
    Route::get('/user_roles/{user}', [RoleController::class, 'getUserRoles']);

    // Statuses
    Route::get('/statuses', [StatusController::class, 'index']);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/employee', [UserController::class, 'registerEmployee']);
    Route::get('/employees', [UserController::class, 'getEmployeeRoles']);
    Route::put('/user/{user}', [UserController::class, 'update']);
    Route::post('/image/{user}', [UserController::class, 'changeUserImage'])->name('test_image_route');
    Route::put('/manage/{user}', [UserController::class, 'manage']);
    Route::put('/password/{user}', [UserController::class, 'changePassword']);
    Route::delete('/user/{user}', [UserController::class, 'destroy']);

    // Warehouses
    Route::resource('/warehouse', WarehouseController::class);
    Route::get('/active_warehouses', [WarehouseController::class, 'getActive']);

});

//Get item image route
Route::get('/test/{image}', [AuctionController::class, 'getItemImage']);

//Get user image route
Route::get('/user/{image}', [UserController::class, 'getUserImage']);






