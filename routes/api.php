<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Dashboard\OrderController as DashboardOrderController;
use App\Http\Controllers\Api\Dashboard\PrescriptionController as DashboardPrescriptionController;
use App\Http\Controllers\Api\Dashboard\UserController;
use App\Http\Controllers\Api\Manager\ManagerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestMailController;

// Route::get('/test-mail', [TestMailController::class, 'send']);
/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::get('/categories', [CategoryController::class, 'all']);
Route::get('/products', [ProductController::class, 'all']);
Route::get('/show/product/{id}', [ProductController::class, 'show']);
Route::get('/product/offers', [ProductController::class, 'offers']);


/*
|--------------------------------------------------------------------------
| Public APIs
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Authenticated APIs
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);

    Route::post('/order', [OrderController::class, 'store']);
    Route::get('/my-orders', [OrderController::class, 'index']);
    Route::get('/show/order/{id}', [OrderController::class, 'show']);

    Route::post('/prescriptions', [PrescriptionController::class, 'store']);
    Route::get('/my-prescriptions', [PrescriptionController::class, 'index']);
    Route::get('/show/prescriptions/{id}', [PrescriptionController::class, 'show']);




    /*
|--------------------------------------------------------------------------
| Manager APIs
|--------------------------------------------------------------------------
*/

    Route::middleware('dashboard')->prefix('manager')->group(function () {

        // crud admin
        Route::post('/add-admin', [ManagerController::class, 'storeAdmin']);
        Route::post('/update-admin/{id}', [ManagerController::class, 'updateAdmin']);
        Route::get('/show-admin/{id}', [ManagerController::class, 'showAdmin']);
        Route::get('/all-admin', [ManagerController::class, 'admins']);
        Route::delete('/delete-admin/{id}', [ManagerController::class, 'destroyAdmin']);

        // all statistics

        // users
        Route::get('/all-users', [ManagerController::class, 'users']);
        Route::get('/show/user/{id}', [ManagerController::class, 'showuser']);

        // products
        Route::get('/all-products', [ManagerController::class, 'product']);
        Route::get('/show/product/{id}', [ManagerController::class, 'showproduct']);

        // orders
        Route::get('/all/orders', [ManagerController::class, 'orders']);
        Route::get('/show/order/{id}', [ManagerController::class, 'showorder']);

        // prescriptions
        Route::get('/all/prescriptions', [ManagerController::class, 'prescriptions']);
        Route::get('/show/prescription/{id}', [ManagerController::class, 'showprescription']);



        Route::get('/all-categorys', [ManagerController::class, 'category']);
    });

/*
|--------------------------------------------------------------------------
| Admin APIs
|--------------------------------------------------------------------------
*/

    Route::middleware('dashboard')->prefix('admin')->group(function () {

        // category CRUD
        Route::post('/create/category', [CategoryController::class, 'store']);
        Route::post('/update/category/{id}', [CategoryController::class, 'update']);
        Route::get('/show/category/{id}', [CategoryController::class, 'show']);
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::delete('/delete/category/{id}', [CategoryController::class, 'destroy']);

        // product CRUD
        Route::post('/create/product', [ProductController::class, 'store']);
        Route::post('/update/product/{id}', [ProductController::class, 'update']);
        Route::get('/show/product/{id}', [ProductController::class, 'show']);
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/product/offers', [ProductController::class, 'offersAdmin']);
        Route::delete('/delete/product/{id}', [ProductController::class, 'destroy']);

        // orders CRUD
        Route::get('/all/orders', [DashboardOrderController::class, 'index']);
        Route::get('/pending/orders', [DashboardOrderController::class, 'pending']);
        Route::get('/show/order/{id}', [DashboardOrderController::class, 'show']);
        Route::post('/update/order/{id}/status', [DashboardOrderController::class, 'updateStatus']);
        Route::delete('/delete/order/{id}', [DashboardOrderController::class, 'destroy']);

        // prescriptions CRUD
        Route::get('/all/prescriptions', [DashboardPrescriptionController::class, 'index']);
        Route::get('/pending/prescriptions', [DashboardPrescriptionController::class, 'pending']);
        Route::get('/show/prescriptions/{id}', [DashboardPrescriptionController::class, 'show']);
        Route::post('/update/prescriptions/{id}/status', [DashboardPrescriptionController::class, 'updateStatus']);
        Route::delete('/delete/prescriptions/{id}', [DashboardPrescriptionController::class, 'destroy']);

        // users CRUD
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/show/user/{id}', [UserController::class, 'show']);
        Route::post('/update/user/{id}/status', [UserController::class, 'updateStatus']);
    });
});
