<?php
use App\Http\Controllers\admin\LoginController as AdminLoginController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;


use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;


use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'account'],function(){
   // guest Middleware
    Route::group(['Middleware' => 'guest'],function(){
 Route::get('login',[LoginController::class,'index'])->name('account.login');
Route::get('ragister',[LoginController::class,'ragister'])->name('account.ragister');
Route::post('process-register',[LoginController::class,'processregister'])->name('account.processregister');
Route::post('authenticate',[LoginController::class,'authenticate'])->name('account.authenticate');

    });

   // auth Middleware
    Route::group(['Middleware' => 'auth'],function(){
        Route::get('logout',[LoginController::class,'logout'])->name('account.logout');
        Route::get('dashboard',[DashboardController::class,'index'])->name('account.dashboard');

        Route::get('products/index', [ProductController::class,'index'])->name('products.index');


        Route::get('products/create', [ProductController::class,'create'])->name('products.create');
        Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');

        Route::get('products/{id}/edit',[ProductController::class,'edit']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::put('/products/{id}/update', [ProductController::class, 'update'])->name('products.update');


        Route::get('products/{id}/show',[ProductController::class,'show']);

 
    });
    
});




Route::group(['prefix' => 'admin'],function(){
    // guest Middleware admin
     Route::group(['Middleware' => 'admin.guest'],function(){
 
        Route::get('login',[AdminLoginController::class,'index'])->name('admin.login');
        Route::post('authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');

     });
 
    // auth Middleware
     Route::group(['Middleware' => 'admin.auth'],function(){
        
        Route::get('dashboard',[AdminDashboardController::class,'index'])->name('admin.dashboard');
        Route::get('logout',[AdminLoginController::class,'logout'])->name('admin.logout');

     });
     
 });





