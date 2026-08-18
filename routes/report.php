<?php
use Illuminate\Support\Facades\Route;
   Route::prefix('report')->name('reports.')->group(function () {
        Route::livewire('sales', 'pages::reports.sales')->name('sales');
        Route::livewire('stock', 'pages::reports.stock')->name('stock');
        Route::livewire('supplier', 'pages::reports.supplier')->name('supplier');
        Route::livewire('/', 'pages::reports.all')->name('index');
    });