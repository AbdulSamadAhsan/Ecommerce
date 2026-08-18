<?php
use Illuminate\Support\Facades\Route;
        Route::prefix("contact-us")->name("contact-us.")->group(function(){
          Route::livewire("/","pages::contacts.all")->name("index");
          Route::livewire('/{id}', 'pages::contacts.show')->name('show');
          Route::livewire('/{id}/edit', 'pages::contacts.edit')->name('edit');
          Route::livewire('/{id}/reply', 'pages::contacts.reply')->name('reply');
        });
  


 
    


   
    Route::prefix('customer-support-tickets')->name('customer-support-tickets.')->group(function () {
     Route::livewire('/', 'pages::tickets.all')->name('index');
     Route::livewire('/{id}/edit', 'pages::tickets.edit')->name('edit');
     Route::livewire('/{id}', 'pages::tickets.show')->name('show');
    });
    