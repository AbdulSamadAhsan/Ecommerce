<?php
use Illuminate\Support\Facades\Route;
   Route::prefix("purchases")->name("purchases.")->group(function () {
     Route::livewire("/create", "pages::purchases.create")->name("create");
     Route::livewire("/history", "pages::purchases.all")->name("history");
     Route::livewire( '/{id}','pages::purchases.show')->name('show');
   });
      Route::prefix('sales')->name('sales.')->group(function () {
        Route::livewire('history', 'pages::sales.history')->name('history');
   
        Route::livewire('invoice', 'pages::sales.invoices')->name('invoice');
        Route::livewire("/{id}","pages::sales.show")->name("show");
    });
          
   Route::name('suppliers.')->group(function () {

    Route::prefix('suppliers')->group(function () {
        Route::livewire('/', 'pages::suppliers.all')->name('index');
        Route::livewire('/create', 'pages::suppliers.create')->name('create');
        Route::livewire('/{id}', 'pages::suppliers.show')->name('show');
        Route::livewire('/{id}/edit', 'pages::suppliers.edit')->name('edit');
        Route::livewire('/payment', 'pages::suppliers.payment.create')->name('payment.create');
    });

      Route::livewire('/suppliers-payments', 'pages::suppliers.payment.index')->name('payment.index');
   });




    Route::prefix("warehouses")->name("warehouses.")->group(function () {
        Route::livewire("/", "pages::warehouses.all")->name("index");
        Route::livewire("/create", "pages::warehouses.create")->name("create");
        Route::livewire('/{id}', 'pages::warehouses.show')->name('show');
        Route::livewire('/{id}/edit', 'pages::warehouses.edit')->name('edit');


    });
       Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::livewire('/', 'pages::stock.stock')->name('index');
    
    });