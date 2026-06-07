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

});