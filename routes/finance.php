<?php
use Illuminate\Support\Facades\Route;
   Route::prefix('expenses')->group(function () {

      Route::livewire('/', 'pages::expenses.all')->name('expenses.index');
      Route::livewire('/create', 'pages::expenses.create')->name('expenses.create');
      Route::livewire('/show/{id}', 'pages::expenses.show')->name('expenses.show');
      

    });
  
   
    Route::prefix('wallet-top-requests')->name('wallet-topups.')->group(function () {
     Route::livewire('/', 'pages::wallettoprequest.all')->name('index');
     Route::livewire('/{id}/edit', 'pages::wallettoprequest.edit')->name('edit');
    });
    Route::prefix('expense-categories')->name('expense-categories.')->group(function () {
     Route::livewire('/', 'pages::expenses.expensecategories.all')->name('index');
     Route::livewire('/create', 'pages::expenses.expensecategories.create')->name('create');
     Route::livewire('/{id}/edit', 'pages::expenses.expensecategories.edit')->name('edit');
     Route::livewire('/{id}', 'pages::expenses.expensecategories.show')->name('show');
    });
    Route::prefix("taxes")->name("taxes.")->group(function () {
        Route::livewire("/", "pages::taxes.all")->name("index");
        Route::livewire("/create", "pages::taxes.create")->name("create");
        Route::livewire("/{id}/edit", "pages::taxes.edit")->name("edit");
        Route::livewire('/{id}', 'pages::taxes.show')->name('show');
    });