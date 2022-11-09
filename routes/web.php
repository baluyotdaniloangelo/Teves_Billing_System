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

/*Load Site*/
Route::get('/billing_list', [BillingTransactionController::class,'billing_list'])->name('billing_list')->middleware('isLoggedIn');
Route::get('site/list', [BillingTransactionController::class, 'getSite'])->name('SiteList')->middleware('isLoggedIn');

/*Create Site*/
#Route::get('/create_site', [BillingTransactionController::class,'create_site'])->name('create_site')->middleware('isLoggedIn');
Route::post('/create_site_post', [BillingTransactionController::class,'create_site_post'])->name('create_site_post')->middleware('isLoggedIn');

/*Update Site*/
#Route::get('/edit_site/{siteID}', [BillingTransactionController::class,'edit_site'])->name('edit_site')->middleware('isLoggedIn');
Route::post('/update_site_post', [BillingTransactionController::class,'update_site_post'])->name('update_site_post')->middleware('isLoggedIn');

/*GET Site Info*/
Route::post('/site_info', [BillingTransactionController::class, 'site_info'])->name('site_info')->middleware('isLoggedIn');

/*Confirm Delete Site*/
Route::post('/delete_site_confirmed', [BillingTransactionController::class, 'delete_site_confirmed'])->name('delete_site_confirmed')->middleware('isLoggedIn');

/*Site Dashboard*/
Route::get('/site_details/{siteID}', [BillingTransactionController::class,'site_details'])->name('site_details')->middleware('isLoggedIn');

/*Save Site Current Tab*/
Route::post('/save_site_tab', [BillingTransactionController::class, 'save_site_tab'])->name('save_site_tab')->middleware('isLoggedIn');

/*Load Gateway List Persite*/
Route::get('getGateway/', [CAMRGatewayController::class,'getGateway'])->name('getGateway')->middleware('isLoggedIn');

/*Create Gateway*/
Route::post('/create_gateway_post', [CAMRGatewayController::class,'create_gateway_post'])->name('create_gateway_post')->middleware('isLoggedIn');

/*Update Gateway*/
Route::post('/update_gateway_post', [CAMRGatewayController::class,'update_gateway_post'])->name('update_gateway_post')->middleware('isLoggedIn');

/*GET Gateway Info*/
Route::post('/gateway_info', [CAMRGatewayController::class, 'gateway_info'])->name('gateway_info')->middleware('isLoggedIn');

/*Confirm Delete Gateway*/
Route::post('/delete_gateway_confirmed', [CAMRGatewayController::class, 'delete_gateway_confirmed'])->name('delete_gateway_confirmed')->middleware('isLoggedIn');


/*Enable CSV Update*/
Route::post('enablecsvUpdate/', [CAMRGatewayController::class,'enablecsvUpdate'])->name('enablecsvUpdate')->middleware('isLoggedIn');

/*Disable CSV Update*/
Route::post('disablecsvUpdate/', [CAMRGatewayController::class,'disablecsvUpdate'])->name('disablecsvUpdate')->middleware('isLoggedIn');

/*Enable Site Code Update*/
Route::post('enablesitecodeUpdate/', [CAMRGatewayController::class,'enablesitecodeUpdate'])->name('enablesitecodeUpdate')->middleware('isLoggedIn');

/*Disable Site Code Update*/
Route::post('disablesitecodeUpdate/', [CAMRGatewayController::class,'disablesitecodeUpdate'])->name('disablesitecodeUpdate')->middleware('isLoggedIn');

/*Enable SSH*/
Route::post('enableSSH/', [CAMRGatewayController::class,'enableSSH'])->name('enableSSH')->middleware('isLoggedIn');

/*Disable SSH*/
Route::post('disableSSH/', [CAMRGatewayController::class,'disableSSH'])->name('disableSSH')->middleware('isLoggedIn');

/*Enable Force Load Profile*/
Route::post('enableLP/', [CAMRGatewayController::class,'enableLP'])->name('enableLP')->middleware('isLoggedIn');

/*Disable Force Load Profile*/
Route::post('disableLP/', [CAMRGatewayController::class,'disableLP'])->name('disableLP')->middleware('isLoggedIn');

/*CHECK TIME*/
Route::get('/check_time.php', [CAMRGatewayDeviceController::class,'check_time']);

//Route::get("/http_post_server.php", [CAMRGatewayDeviceController::class,'http_post_server']);
Route::get('/http_post_server.php', [CAMRGatewayDeviceController::class, 'http_post_server']);

/*GATEWAY Access to Site Information, Gateway and Meter List*/


