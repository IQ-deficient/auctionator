<?php

use App\Http\Controllers\UserController;
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
    Route::post('/login', [AuthController::class, 'login'])->middleware('logged_in');
    Route::post('/register', [AuthController::class, 'register'])->middleware('logged_in');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user', [AuthController::class, 'userProfile']);
    Route::get('/image', [AuthController::class, 'userImage']);
});

// Routes that everyone has access to no matter if Authenticated
Route::post('/filtered_auctions', [AuctionController::class, 'getFiltered']);   // Live auctions by Category
Route::get('/menu_categories', [CategoryController::class, 'getMenuCategories']);   // All Categories formatted for Menu
Route::get('/active_countries', [CountryController::class, 'getActive']);  // Used in registration
Route::post('/contact_us', [UserController::class, 'sendMail']);        // Contact us page
Route::get('/item/{image}', [AuctionController::class, 'getItemImage']);    // Get item image route
Route::get('/user/{image}', [UserController::class, 'getUserImage']);   // Get user image route

// This Middleware should require User being Authenticated and his JWT token to be granted access to these routes
Route::group([
    'middleware' => ['auth:api', 'check_user']
], function () {

    // Auctions
    Route::post('/auction', [AuctionController::class, 'store']);
    Route::get('/auctions', [AuctionController::class, 'index']);
//    Route::get('/active_auctions', [AuctionController::class, 'getActive']);
    Route::get('/auction/{auction}', [AuctionController::class, 'show']);       // Show one specific Auction by ID
    Route::put('/auction/{auction}', [AuctionController::class, 'update']);
    Route::delete('/auction/{auction}', [AuctionController::class, 'destroy']);
    Route::delete('/auction_soft/{auction}', [AuctionController::class, 'softDestroyAndRestore']);     // NA

    // Bids
    Route::post('/bid', [BidController::class, 'store']);      // Placing a Bid on the Auction
//    Route::get('/bids', [BidController::class, 'index']);
    Route::get('/user_bids', [BidController::class, 'getBidsForUser']);     // Auth User bids

    // Categories
//    Route::get('/categories', [CategoryController::class, 'index']);
//    Route::get('/active_categories', [CategoryController::class, 'getActive']);
    Route::get('/parent_categories', [CategoryController::class, 'getParentCategories']);       // core categories
    Route::get('/category/{category}', [CategoryController::class, 'show']);
    Route::post('/child_categories_conditions', [CategoryController::class, 'getChildCategoriesAndConditions']);    // for master category

    // CategoryCondition
//    Route::get('/category_conditions', [CategoryConditionController::class, 'index']);

    // Conditions
//    Route::get('/conditions', [ConditionController::class, 'index']);

    // Countries
//    Route::get('/countries', [CountryController::class, 'index']);
//    Route::get('/country/{country}', [CountryController::class, 'show']);

    // Genders
    Route::get('/genders', [GenderController::class, 'index']);

    // Histories
    Route::post('/history', [HistoryController::class, 'store']);   // Buyout
//    Route::get('/histories', [HistoryController::class, 'index']);
    Route::get('/user_histories', [HistoryController::class, 'getHistoriesForUser']);   // Auth User history
//    Route::get('/history/{history}', [HistoryController::class, 'show']);
    //Route::put('/history/{history}', [HistoryController::class, 'update']);
    //Route::delete('/history/{history}', [HistoryController::class, 'destroy']);

    // Images
//    Route::get('/images', [ImageController::class, 'index']);

    // Items
//    Route::get('/items', [ItemController::class, 'index']);
    Route::post('/images/{item}', [ItemController::class, 'addItemImage']);       // store image test

    // Roles
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/auth_roles', [RoleController::class, 'getAuthUserRoles']);
    Route::get('/user_roles/{user}', [RoleController::class, 'getUserRoles']);

    // Statuses
    Route::get('/statuses', [StatusController::class, 'index']);

    // Users
    Route::get('/users', [UserController::class, 'index']);     // Client and Employees
    Route::post('/employee', [UserController::class, 'registerEmployee']);      // Administrator inserts new personnel
    Route::get('/employees', [UserController::class, 'getEmployeeRoles']);      // Worker roles
    Route::put('/user/{user}', [UserController::class, 'update']);     // update profile auth user
    Route::post('/image/{user}', [UserController::class, 'changeUserImage'])->name('test_image_route');       // update image
    Route::put('/manage/{user}', [UserController::class, 'manage']);     // manage user profiles
    Route::put('/password/{user}', [UserController::class, 'changePassword']);       // update password
    Route::delete('/user/{user}', [UserController::class, 'destroy']);      // deactivate user

    // UserRoles
//    Route::get('/user_roles', [UserRoleController::class, 'index']);

    // Warehouses (Admin)
    Route::resource('/warehouse', WarehouseController::class);
//    Route::post('/warehouse', [WarehouseController::class, 'store']);
//    Route::get('/warehouses', [WarehouseController::class, 'index']);
    Route::get('/active_warehouses', [WarehouseController::class, 'getActive']);
//    Route::put('/warehouse/{warehouse}', [WarehouseController::class, 'update']);
//    Route::delete('/warehouse/{warehouse}', [WarehouseController::class, 'destroy']);

});







