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
    Route::livewire('/{id}', 'pages::products.show')
    ->name('show');
});
Route::prefix('category')->name("categories.")->group(function () {
    Route::livewire('/create', 'pages::categories.add')->name('create');
   Route::livewire('/{id}', 'pages::categories.show')->name('show');
    Route::livewire('/', 'pages::categories.categories')->name('index');
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
   Route::livewire('/{id}', 'pages::brands.show')->name('show');

});
Route::prefix("suppliers")->name("suppliers.")->group(function(){
   Route::livewire("/","pages::suppliers.all")->name("index");
   Route::livewire("/create","pages::suppliers.create")->name("create");
 
Route::livewire('/{id}', 'pages::suppliers.show')->name('suppliers.show');  
});
Route::prefix("warehouses")->name("warehouses.")->group(function(){
   Route::livewire("/","pages::warehouses.all")->name("index");
   Route::livewire("/create","pages::warehouses.create")->name("create");

 Route::livewire('/{id}', 'pages::warehouses.show')->name('show');
});
Route::prefix("departments")->name("departments.")->group(function(){
   Route::livewire("/","pages::departments.all")->name("index");
   Route::livewire("/create","pages::departments.create")->name("create");
  

});
Route::prefix("employees")->name("employees.")->group(function(){
   Route::livewire("/","pages::employees.all")->name("index");
   Route::livewire("/create","pages::employees.create")->name("create");
  

});
Route::livewire("/","pages::frontend.home")->name("front");
Route::livewire("/cart","pages::frontend.cart")->name("cart");
Route::livewire("/checkout","pages::frontend.checkout")->name("checkout");
Route::livewire('/customers/{id}', 'pages::customers.show')
    ->name('customers.show');
Route::livewire('/product/{id}', 'pages::frontend.products.product-detail')
    ->name('product.detail');
Route::prefix('customer')->name('customer.')->group(function () {
    Route::livewire('/dashboard', 'pages::frontend.customer.dashboard')->name('dashboard');
    Route::livewire('/orders', 'pages::frontend.customer.orders')->name('orders');
    Route::livewire('/orders/{id}', 'pages::frontend.customer.order-detail')->name('order.detail');
    Route::livewire('/returns', 'pages::frontend.customer.returns')->name('returns');
    Route::livewire('/wallet', 'pages::frontend.customer.wallet')->name('wallet');
    Route::livewire('/profile', 'pages::frontend.customer.profile')->name('profile');
    Route::livewire("/wallet/add","pages::frontend.customer.wallet.add")->name("wallet.add");
    Route::livewire("/login","pages::frontend.login")->name("login");
    Route::livewire("/register","pages::frontend.register")->name("register");
    Route::livewire("/forget_password","pages::frontend.forget_password")->name("forget_password");
    Route::livewire("/reset_password/{token}","pages::frontend.reset_password")->name("password_reset");
      Route::livewire('/contact-us', 'pages::frontend.customer.contact-us')->name('contact-us');
        Route::livewire('/support-ticket', 'pages::frontend.customer.support-ticket')
    ->name('support.ticket');
    Route::livewire('/my-support-tickets', 'pages::frontend.customer.my-support-ticket')
    ->name('my.support.tickets');
    Route::livewire('/support-tickets/{ticketNo}', 'pages::frontend.customer.ticket-detail')
    ->name('ticket.detail');
    Route::livewire('/wishlist', 'pages::frontend.customer.wishlist')
    ->name('wishlist');
    
});