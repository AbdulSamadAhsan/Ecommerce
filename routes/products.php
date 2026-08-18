<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductReportController;
 Route::prefix('category')->name("categories.")->group(function () {
        Route::livewire('/', 'pages::categories.categories')->name('index');
        Route::livewire('/create', 'pages::categories.add')->name('create');
        Route::livewire('/{id}', 'pages::categories.show')->name('show');
        Route::livewire('/{id}/edit','pages::categories.edit')->name('edit');
   });   

       Route::prefix("brands")->name("brands.")->group(function () {
        Route::livewire("/", "pages::brands.all")->name("index");
        Route::livewire("/create", "pages::brands.create")->name("create");
        Route::livewire("/{id}/edit", "pages::brands.edit")->name("edit");
        Route::livewire('/{id}', 'pages::brands.show')->name('show');
    });
 Route::name('products.')->group(function () {
   Route::prefix('products')->group(function () {
        Route::livewire('/', 'pages::products.all')->name('index');
        Route::livewire('/create', 'pages::products.create')->name('create');
        Route::livewire('/{id}/edit', 'pages::products.edit')->name('edit');
        Route::livewire('/{id}', 'pages::products.show')->name('show');
        Route::get('/reports/products',ProductReportController::class)->name('report');
   });     
   Route::prefix('product-review')->group(function () {
    Route::livewire('/', 'pages::products.review')->name('review');
    Route::livewire('/{id}/edit', 'pages::products.review_edit')->name('review.edit');
   });
 });