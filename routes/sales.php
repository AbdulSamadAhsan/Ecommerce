<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderReportController;
   Route::prefix('orders')->name('orders.')->group(function () {
     Route::livewire('/', 'pages::orders.all')->name('index');
     Route::livewire('/create', 'pages::orders.create')->name('create');
     Route::livewire('/{id}/edit', 'pages::orders.edit')->name('edit');
     Route::livewire('/{id}', 'pages::orders.show')->name('show');
   });
       Route::prefix("coupons")->name("coupons.")->group(function () {
        Route::livewire("/", "pages::coupons.all")->name("index");
        Route::livewire("/create", "pages::coupons.create")->name("create");
        Route::livewire("/{id}/edit", "pages::coupons.edit")->name("edit");
        Route::livewire('/{id}', 'pages::coupons.show')->name('show');
    });
    
    Route::get("/order/report",OrderController::class)->name("order.report");
     
    Route::get("/order/{id}/report",OrderReportController::class)->name("order.invoice.report");
   
    Route::prefix("customers")->name("customers.")->group(function () {
        Route::livewire('/', 'pages::customers.all')->name('index');
        Route::livewire('/{id}', 'pages::customers.show')->name('show');
    });