<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\CancellationRequestController;
use App\Http\Controllers\NotificationController;
use Illuminate\Routing\RouteGroup;
use Symfony\Component\Routing\Route as RoutingRoute;

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


Route::get('/admin', [AdminController::class, "index"])->middleware("auth");

Route::get("/", [UserController::class, 'show'])->middleware("auth");
Route::resource("/holiday", HolidayController::class);
Route::post("/request/store", [HolidayController::class, 'store']);
Route::get("/my-account", [UserController::class, 'edit']);
//cancellation requests
Route::post('/user/cancellation-request', [CancellationRequestController::class, 'store']);
//admin
Route::put('/admin/cancellation-requests/update', [CancellationRequestController::class, 'update']);
//notifications
Route::get('/notifications', [NotificationController::class, 'index']);
Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead']);

