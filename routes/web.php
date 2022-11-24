<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\BillingTransactionController;
use App\Http\Controllers\CAMRGatewayController;
use App\Http\Controllers\CAMRGatewayDeviceController;


use App\Http\Controllers\CAMRSampleExcel;
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

/*SAMPLE EXCEL*/
Route::get('/sample1', [CAMRSampleExcel::class,'sample1'])->name('site')->middleware('isLoggedIn');

/*Login Page*/
Route::get('/',[UserAuthController::class,'login'])->middleware('alreadyLoggedIn');
Route::post('login-user', [UserAuthController::class,'loginUser'])->name('login-user');

/*Logout*/
Route::get('logout', [UserAuthController::class,'logout']);

/*Load Billing Transaction*/
Route::get('/billing', [BillingTransactionController::class,'billing'])->name('billing')->middleware('isLoggedIn');
Route::get('billing/list', [BillingTransactionController::class, 'getBillingTransactionList'])->name('getBillingTransactionList')->middleware('isLoggedIn');

/*Create Site*/
Route::post('/billingtransaction_post', [BillingTransactionController::class,'billingtransaction_post'])->name('billingtransaction_post')->middleware('isLoggedIn');

/*Update Site*/
Route::post('/update_site_post', [BillingTransactionController::class,'update_site_post'])->name('update_site_post')->middleware('isLoggedIn');

/*GET Site Info*/
Route::post('/site_info', [BillingTransactionController::class, 'site_info'])->name('site_info')->middleware('isLoggedIn');

/*Confirm Delete Site*/
Route::post('/delete_site_confirmed', [BillingTransactionController::class, 'delete_site_confirmed'])->name('delete_site_confirmed')->middleware('isLoggedIn');

/*Site Dashboard*/
Route::get('/site_details/{siteID}', [BillingTransactionController::class,'site_details'])->name('site_details')->middleware('isLoggedIn');

