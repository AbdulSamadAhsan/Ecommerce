<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\customer;
Route::livewire("/checkout", "pages::frontend.checkout")->name("checkout")->middleware("customer"); 



Route::name('customer.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Customer Order Detail
    |--------------------------------------------------------------------------
    */

    Route::livewire('/customer_order/{order}', 'pages::orders.detail')
        ->name('orders.show');


    /*
    |--------------------------------------------------------------------------
    | Customer Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('customer')->group(function () {

        /*
        |----------------------------------------------------------------------
        | Guest / Public Routes
        |----------------------------------------------------------------------
        */

        Route::livewire('/login', 'pages::frontend.login')
            ->name('login');

        Route::livewire('/register', 'pages::frontend.register')
            ->name('register');

        Route::livewire('/forget_password', 'pages::frontend.forget_password')
            ->name('forget_password');

        Route::livewire('/reset_password/{token}', 'pages::frontend.reset_password')
            ->name('password_reset');

        Route::livewire('/contact-us', 'pages::frontend.customer.contact-us')
            ->name('contact-us');


        /*
        |----------------------------------------------------------------------
        | Authenticated Customer Routes
        |----------------------------------------------------------------------
        */

        Route::middleware('customer')->group(function () {

            // Dashboard
            Route::livewire('/dashboard', 'pages::frontend.customer.dashboard')
                ->name('dashboard');


            // Orders
            Route::livewire('/orders', 'pages::frontend.customer.orders')
                ->name('orders');

            Route::livewire('/orders/{id}', 'pages::frontend.customer.order-detail')
                ->name('order.detail');


            // Addresses
            Route::livewire('/addresses', 'pages::frontend.customer.addresses')
                ->name('addresses');


            // Returns
            Route::livewire('/returns', 'pages::frontend.customer.returns')
                ->name('returns');


            // Wallet
            Route::livewire('/wallet', 'pages::frontend.customer.wallet')
                ->name('wallet');

            Route::livewire('/wallet/add', 'pages::frontend.customer.wallet.add')
                ->name('wallet.add');


            // Profile
            Route::livewire('/profile', 'pages::frontend.customer.profile')
                ->name('profile');


            // Support Tickets
            Route::livewire('/support-ticket', 'pages::frontend.customer.support-ticket')
                ->name('support.ticket');

            Route::livewire('/my-support-tickets', 'pages::frontend.customer.my-support-ticket')
                ->name('my.support.tickets');

            Route::livewire('/support-tickets/{ticketNo}', 'pages::frontend.customer.ticket-detail')
                ->name('ticket.detail');


            // Wishlist
            Route::livewire('/wishlist', 'pages::frontend.customer.wishlist')
                ->name('wishlist');


            // Logout
            Route::get('/logout', function () {

                auth('customer')->logout();

                request()->session()->invalidate();
                request()->session()->regenerateToken();

                return redirect()->route('customer.login');

            })->name('logout');
        });
    });
});
 Route::livewire('/ai-assistant', "pages::customer.mcp")->name('customer.ai.assistant');

 Route::livewire('/customer/ai', 'pages::customer.ai')
    ->name('customer.ai.chatbot');