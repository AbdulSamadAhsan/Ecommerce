<?php

use Illuminate\Support\Facades\Route;



Auth::routes();
  Route::middleware(["auth"])->group(function(){

  });
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::livewire("dashboard", "pages::dashboard")->name('dashboard')->middleware("auth");
Route::livewire('/categories', 'pages::categories.categories')->name('categories');
Route::prefix('products')->name("products.")->group(function () {
    Route::livewire('/create', 'pages::products.create')->name('create');
    Route::livewire('/edit/{id}', 'pages::products.edit')->name('edit');
    Route::livewire('/', 'pages::products.all')->name('index');
});
Route::prefix('report')->name('reports.')->group(function () {
    Route::livewire('sales', 'pages::reports.sales')->name('sales');
    Route::livewire('stock', 'pages::reports.stock')->name('stock');
    Route::livewire('supplier', 'pages::reports.supplier')->name('supplier');
});
Route::prefix('sales')->name('sales.')->group(function () {
    Route::livewire('history', 'pages::sales.history')->name('histroy');
    Route::livewire('create', 'pages::sales.create')->name('create');
    Route::livewire('invoice', 'pages::sales.invoices')->name('invoice');

});

Route::prefix("brands")->name("brands.")->group(function(){
   Route::livewire("/","pages::brands.all")->name("index");
   Route::livewire("/create","pages::brands.create")->name("create");
   Route::livewire("/edit/{id}","pages::brands.edit")->name("edit");

});
Route::livewire("/","pages::frontend.home")->name("front");
Route::livewire("/cart","pages::frontend.cart")->name("cart");
Route::livewire("/checkout","pages::frontend.checkout")->name("checkout");
Route::livewire("customer/register","pages::frontend.register")->name("customer.register");
Route::livewire("customer/login","pages::frontend.login")->name("customer.login");
Route::livewire("customer/forget_password","pages::frontend.forget_password")->name("customer.forget_password");
Route::livewire("customer/reset_password/{token}","pages::frontend.reset_password")->name("customer.password_reset");

Route::prefix('customer')->name('customer.')->group(function () {
    Route::livewire('/dashboard', 'pages::frontend.customer.dashboard')->name('dashboard');
    Route::livewire('/orders', 'pages::frontend.customer.orders')->name('orders');
    Route::livewire('/orders/{id}', 'pages::frontend.customer.order-detail')->name('order.detail');
    Route::livewire('/returns', 'pages::frontend.customer.returns')->name('returns');
    Route::livewire('/wallet', 'pages::frontend.customer.wallet')->name('wallet');
    Route::livewire('/profile', 'pages::frontend.customer.profile')->name('profile');
});
