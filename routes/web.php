<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\BillingTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReportController;

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

/*SAMPLE EXCEL
Route::get('/sample1', [CAMRSampleExcel::class,'sample1'])->name('site')->middleware('isLoggedIn');
*/

/*Login Page*/
Route::get('/',[UserAuthController::class,'login'])->middleware('alreadyLoggedIn');
Route::post('login-user', [UserAuthController::class,'loginUser'])->name('login-user');
/*Logout*/
Route::get('logout', [UserAuthController::class,'logout']);

/*Load Billing Transaction*/
Route::get('/billing', [BillingTransactionController::class,'billing'])->name('billing')->middleware('isLoggedIn');
Route::get('billing/list', [BillingTransactionController::class, 'getBillingTransactionList'])->name('getBillingTransactionList')->middleware('isLoggedIn');
/*Create Bill*/
Route::post('/create_bill_post', [BillingTransactionController::class,'create_bill_post'])->name('create_bill_post')->middleware('isLoggedIn');
/*Update Bill*/
Route::post('/update_bill_post', [BillingTransactionController::class,'update_bill_post'])->name('update_bill_post')->middleware('isLoggedIn');
/*GET Bill Info*/
Route::post('/bill_info', [BillingTransactionController::class, 'bill_info'])->name('bill_info')->middleware('isLoggedIn');
/*Confirm Delete Bill*/
Route::post('/delete_bill_confirmed', [BillingTransactionController::class, 'delete_bill_confirmed'])->name('delete_bill_confirmed')->middleware('isLoggedIn');

/*Dev Date Nov 30 2022*/
/*Load Product List*/
Route::get('/product', [ProductController::class,'product'])->name('product')->middleware('isLoggedIn');
Route::get('product/list', [ProductController::class, 'getProductList'])->name('getProductList')->middleware('isLoggedIn');
/*Create Product*/
Route::post('/create_product_post', [ProductController::class,'create_product_post'])->name('create_product_post')->middleware('isLoggedIn');
/*GET Product Info*/
Route::post('/product_info', [ProductController::class, 'product_info'])->name('product_info')->middleware('isLoggedIn');
/*Update Product*/
Route::post('/update_product_post', [ProductController::class,'update_product_post'])->name('update_product_post')->middleware('isLoggedIn');
/*Confirm Delete Product*/
Route::post('/delete_product_confirmed', [ProductController::class, 'delete_product_confirmed'])->name('delete_product_confirmed')->middleware('isLoggedIn');

/*Dev Date Nov 30 2022*/
/*Load Client List*/
Route::get('/client', [ClientController::class,'client'])->name('client')->middleware('isLoggedIn');
Route::get('client/list', [ClientController::class, 'getClientList'])->name('getClientList')->middleware('isLoggedIn');
/*Create Product*/
Route::post('/create_client_post', [ClientController::class,'create_client_post'])->name('create_client_post')->middleware('isLoggedIn');
/*GET Product Info*/
Route::post('/client_info', [ClientController::class, 'client_info'])->name('client_info')->middleware('isLoggedIn');
/*Update Product*/
Route::post('/update_client_post', [ClientController::class,'update_client_post'])->name('update_client_post')->middleware('isLoggedIn');
/*Confirm Delete Product*/
Route::post('/delete_client_confirmed', [ClientController::class, 'delete_client_confirmed'])->name('delete_client_confirmed')->middleware('isLoggedIn');

/*Load Report Interface*/
Route::get('/report', [ReportController::class,'report'])->name('report')->middleware('isLoggedIn');
/*Generate via Web Page View*/
Route::post('/generate_report', [ReportController::class,'generate_report'])->name('generate_report')->middleware('isLoggedIn');
/*Download Directly via Excel*/
Route::get('/generate_report_excel', [ReportController::class,'generate_report_excel'])->name('generate_report_excel')->middleware('isLoggedIn');