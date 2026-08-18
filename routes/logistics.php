    <?php

    use Illuminate\Support\Facades\Route;
    Route::prefix("deliveryboys")->name("deliveryboys.")->group(function () {
        Route::livewire("/", "pages::deliveryboys.all")->name("index");
        Route::livewire("/create", "pages::deliveryboys.create")->name("create");
        Route::livewire("/{id}/edit", "pages::deliveryboys.edit")->name("edit");
        Route::livewire('/{id}', 'pages::deliveryboys.show')->name('show');
    });
      Route::prefix("delivery-assignment")->name("delivery-boy-assignments.")->group(function(){
        Route::livewire("/","pages::deliveryboys.orders.deliveryassignment.all")->name("index");
        Route::livewire("/create","pages::deliveryboys.orders.deliveryassignment.create")->name("create");
        Route::livewire("/{id}/edit","pages::deliveryboys.orders.deliveryassignment.edit")->name("edit");
        Route::livewire("/{id}","pages::deliveryboys.orders.deliveryassignment.show")->name("show");

    });
     Route::prefix("shipping-methods")->name('shipping-methods.')->group(function(){
       Route::livewire('/', 'pages::shipments.shipping-methods.all')->name('index');
       Route::livewire('/create', 'pages::shipments.shipping-methods.create')->name('create');
       Route::livewire('/{id}/edit', 'pages::shipments.shipping-methods.edit')->name('edit');
       Route::livewire('/{id}', 'pages::shipments.shipping-methods.show')->name('show');
     });
       Route::prefix("shipments")->name("shipments.")->group(function(){
      Route::livewire("/","pages::shipments.all")->name("index");
      Route::livewire("/create","pages::shipments.create")->name("create");
      Route::livewire("/{id}/edit","pages::shipments.edit")->name("edit");
      Route::livewire("/{id}","pages::shipments.show")->name("show");

   });