<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClusterManagement;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\GroupRightsController;
use App\Http\Controllers\UserManagement;

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
Route::post('/auth/register', [AuthController::class, 'register']);

Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/auth/check-session', [AuthController::class,'checktoken']);

Route::post('/auth/update', [UserManagement::class,'update']);

Route::get('/auth/verification-email/{id}',[AuthController::class, 'verifemail']);

Route::post('/auth/verification-email/resend',[AuthController::class, 'resend']);

Route::post('/auth/add-cluster', [ClusterManagement::class, 'AddCluster']);

Route::get('/auth/get-cluster', [ClusterManagement::class, 'GetCluster']);

Route::post('/auth/update-cluster', [ClusterManagement::class, 'UpdateCluster']);

Route::post('/auth/GetOneCluster', [ClusterManagement::class, 'GetOneCluster']);

Route::delete('auth/delete-cluster/{id}', [ClusterManagement::class, 'DeleteCluster']);

Route::post('/auth/add-rights',[GroupRightsController::class, 'AddRights']);

Route::get('/auth/get-rights', [GroupRightsController::class, 'GetRights']);

Route::post('/auth/get-one-right',[GroupRightsController::class,'getOneRight']);

Route::post('/auth/update-right', [GroupRightsController::class, 'UpdateRight']);

Route::delete('/auth/delete-right/{id}', [GroupRightsController::class, 'DeleteRight']);

Route::get('/auth/getContacts',[ContactsController::class, 'getListContacts']);

Route::get('/auth/get-one-contact/{id}',[ContactsController::class,'getOneContact']);

Route::post('/auth/add-contact',[ContactsController::class,'addContact']);

Route::delete('/auth/delete-contact/{id}', [ContactsController::class, 'deleteContact']);

Route::post('/auth/update-contact', [ContactsController::class, 'UpdateContact']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/', function(Request $request) {
        return auth()->user();
    });

    Route::post('/auth/logout', [AuthController::class, 'logout']);
});