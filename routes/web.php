<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\customer;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductReportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderReportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])
    ->name('home')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::livewire("/", "pages::frontend.home")->name("front");
Route::livewire("/cart", "pages::frontend.cart")->name("cart");

Route::livewire('/product/{value}', 'pages::frontend.products.product-detail')
    ->name('product.detail');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::livewire("dashboard", "pages::dashboard")->name('dashboard');

    Route::livewire('/admin/ai-assistant', 'pages::admin.mcp-inventory-assistant')
        ->name('admin.ai.assistant');

    Route::prefix('products')->name("products.")->group(function () {
        Route::livewire('/', 'pages::products.all')->name('index');
        Route::livewire('/create', 'pages::products.create')->name('create');
        Route::livewire('/{id}/edit', 'pages::products.edit')->name('edit');
        Route::livewire('/{id}', 'pages::products.show')->name('show');
        
         Route::get(
    '/reports/products',
    ProductReportController::class
)->name('report');
    });
  Route::livewire("product_review","pages::products.review")->name("products.review");
  Route::livewire("product_review/{id}/edit","pages::products.review_edit")->name("products.review.edit");
Route::prefix("shipments")->name("shipments.")->group(function(){
   Route::livewire("/","pages::shipments.all")->name("index");
      Route::livewire("/create","pages::shipments.create")->name("create");
       Route::livewire("/{id}/edit","pages::shipments.edit")->name("edit");
        Route::livewire("/{id}","pages::shipments.show")->name("show");

});

Route::prefix("purchases")->name("purchases.")->group(function () {
    Route::livewire("/create", "pages::purchases.create")->name("create");
    Route::livewire("/history", "pages::purchases.all")->name("history");
    Route::livewire(
    '/purchases/{id}',
    'pages::purchases.show'
)->name('show');
});
    Route::prefix('category')->name("categories.")->group(function () {
        Route::livewire('/', 'pages::categories.categories')->name('index');
        Route::livewire('/create', 'pages::categories.add')->name('create');
        Route::livewire('/{id}', 'pages::categories.show')->name('show');
        Route::livewire('/{id}/edit', 'pages::categories.edit')
    ->name('edit');
    });

    Route::prefix('report')->name('reports.')->group(function () {
        Route::livewire('sales', 'pages::reports.sales')->name('sales');
        Route::livewire('stock', 'pages::reports.stock')->name('stock');
        Route::livewire('supplier', 'pages::reports.supplier')->name('supplier');
             Route::livewire('/', 'pages::reports.all')->name('index');
    });
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::livewire('/', 'pages::stock.stock')->name('index');
    
    });

    Route::prefix("deliveryassignment")->name("delivery-boy-assignments.")->group(function(){
         Route::livewire("/","pages::deliveryboys.orders.deliveryassignment.all")->name("index");
        Route::livewire("/create","pages::deliveryboys.orders.deliveryassignment.create")->name("create");
                Route::livewire("/{id}/edit","pages::deliveryboys.orders.deliveryassignment.edit")->name("edit");
                        Route::livewire("/{id}","pages::deliveryboys.orders.deliveryassignment.show")->name("show");

    });


    Route::livewire('/shipping-methods', 'pages::shipments.shipping-methods.all')
    ->name('shipping-methods.index');

Route::livewire('/shipping-methods/create', 'pages::shipments.shipping-methods.create')
    ->name('shipping-methods.create');

Route::livewire('/shipping-methods/{id}/edit', 'pages::shipments.shipping-methods.edit')
    ->name('shipping-methods.edit');

Route::livewire('/shipping-methods/{id}', 'pages::shipments.shipping-methods.show')
    ->name('shipping-methods.show');
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::livewire('history', 'pages::sales.history')->name('history');
   
        Route::livewire('invoice', 'pages::sales.invoices')->name('invoice');
        Route::livewire("/{id}","pages::sales.show")->name("show");
    });

    Route::prefix("brands")->name("brands.")->group(function () {
        Route::livewire("/", "pages::brands.all")->name("index");
        Route::livewire("/create", "pages::brands.create")->name("create");
        Route::livewire("/edit/{id}", "pages::brands.edit")->name("edit");
        Route::livewire('/{id}', 'pages::brands.show')->name('show');
    });
           Route::livewire('suppliers/payment', 'pages::suppliers.payment.create')
    ->name('suppliers.payment.create');
             Route::livewire('suppliers_payments', 'pages::suppliers.payment.index')
    ->name('suppliers.payment.index');
    Route::prefix("suppliers")->name("suppliers.")->group(function () {
        Route::livewire("/", "pages::suppliers.all")->name("index");
        Route::livewire("/create", "pages::suppliers.create")->name("create");
       
          Route::livewire("/{id}", "pages::suppliers.show")->name("show");
         
          Route::livewire('/{id}/edit', 'pages::suppliers.edit')
    ->name('edit');
    });

    Route::prefix("warehouses")->name("warehouses.")->group(function () {
        Route::livewire("/", "pages::warehouses.all")->name("index");
        Route::livewire("/create", "pages::warehouses.create")->name("create");
        Route::livewire('/{id}', 'pages::warehouses.show')->name('show');
Route::livewire('/{id}/edit', 'pages::warehouses.edit')
    ->name('edit');


    });

    Route::prefix("departments")->name("departments.")->group(function () {
        Route::livewire("/", "pages::departments.all")->name("index");
        Route::livewire("/create", "pages::departments.create")->name("create");
        Route::livewire("/{id}", "pages::departments.show")->name("show");
        Route::livewire(
    '/{id}/edit',
    'pages::departments.edit'
)->name('edit');
    });

    Route::prefix("institutions")->name("institutions.")->group(function () {
        Route::livewire("/", 'pages::institutions.all')->name('index');
        Route::livewire("/create", 'pages::institutions.create')->name('create');
        Route::livewire("/{id}", 'pages::institutions.show')->name('show');
        Route::livewire("/{id}/edit", 'pages::institutions.edit')->name('edit');
    });

    Route::prefix("educations")->name("educations.")->group(function () {
        Route::livewire("/", 'pages::educations.all')->name('index');
        Route::livewire("/create", 'pages::educations.create')->name('create');
        Route::livewire("/{id}", 'pages::educations.show')->name('show');
        Route::livewire("/{id}/edit", 'pages::educations.edit')->name('edit');
    });

    Route::prefix("employees")->name("employees.")->group(function () {
        Route::livewire("/", "pages::employees.all")->name("index");
        Route::livewire("/create", "pages::employees.create")->name("create");
        Route::livewire("/{id}", "pages::employees.show")->name("show");
             Route::livewire("/{id}/edit", "pages::employees.edit")->name("edit");
         Route::livewire("/salary_payment", "pages::employees.salary")->name("salary");

Route::get('/{employee}/report', [ReportController::class, 'report'])
    ->name('employees.report');
 Route::get(
    '/{employee}/cnic/download',
    [EmployeeController::class, 'downloadCnic']
)->name('employees.cnic.download');
 Route::get(
    '/{employee}/card/download',
    [EmployeeController::class, 'downloadCard']
)->name('card.download');
 Route::get(
    '/{employee}/{salary_payment_id}/payslip/download',
    [EmployeeController::class, 'downloadPayslip']
)->name('payslip.download');
 


});
Route::livewire("employee_document/create","pages::employees.employee_documents.create")
->name("employees.documents.create");
Route::livewire("employee_document","pages::employees.employee_documents.all")
->name("employees.documents.index");
Route::livewire("employee_card/create","pages::employees.employeecard.issue")
->name("employees.employee_card.create");
Route::livewire("employee_card","pages::employees.employeecard.all")
->name("employees.employee_card");
 Route::get("all_employee",[ReportController::class,"allemployee"])->name("employees.all");   
        Route::prefix("salaries")->name("salaries.")->group(function () {
        Route::livewire("/", "pages::employees.salary")->name("all");
      

    });
Route::prefix('payrolls')->name('payrolls.')->group(function () {
    Route::livewire('/', 'pages::payrolls.all')->name('index');
    Route::livewire('/{id}', 'pages::payrolls.show')->name('show');
});


       Route::prefix("attendance")->name("attendances.")->group(function(){
          
              Route::livewire("/","pages::attendance.all")->name("index");
       });
        Route::prefix("leave")->name("leaves.")->group(function(){
          
              Route::livewire("/","pages::leaves.all")->name("index");
       });
    Route::prefix("taxes")->name("taxes.")->group(function () {
        Route::livewire("/", "pages::taxes.all")->name("index");
        Route::livewire("/create", "pages::taxes.create")->name("create");
        Route::livewire("/edit/{id}", "pages::taxes.edit")->name("edit");
        Route::livewire('/{id}', 'pages::taxes.show')->name('show');
    });

    Route::prefix("coupons")->name("coupons.")->group(function () {
        Route::livewire("/", "pages::coupons.all")->name("index");
        Route::livewire("/create", "pages::coupons.create")->name("create");
        Route::livewire("/edit/{id}", "pages::coupons.edit")->name("edit");
        Route::livewire('/{id}', 'pages::coupons.show')->name('show');
    });

     Route::prefix("deliveryboys")->name("deliveryboys.")->group(function () {
        Route::livewire("/", "pages::deliveryboys.all")->name("index");
        Route::livewire("/create", "pages::deliveryboys.create")->name("create");
        Route::livewire("/edit/{id}", "pages::deliveryboys.edit")->name("edit");
        Route::livewire('/{id}', 'pages::deliveryboys.show')->name('show');
    });
    Route::livewire("contact-us","pages::contacts.all")->name("contact-us.index");
    Route::livewire('/contact-us/{id}', 'pages::contacts.show')
    ->name('contact-us.show');
    Route::livewire('/contact-us/{id}/edit', 'pages::contacts.edit')
    ->name('contact-us.edit');
    Route::livewire('/contact-us/{id}/reply', 'pages::contacts.reply')
    ->name('contact-us.reply');
    Route::prefix("customers")->name("customers.")->group(function () {
        Route::livewire('/', 'pages::customers.all')->name('index');
        Route::livewire('/{id}', 'pages::customers.show')->name('show');
    });


 Route::prefix('expenses')->group(function () {

    Route::livewire('/', 'pages::expenses.all')
        ->name('expenses.index');

    Route::livewire('/create', 'pages::expenses.create')
        ->name('expenses.create');



    Route::livewire('/show/{id}', 'pages::expenses.show')
        ->name('expenses.show');
      

});
Route::prefix("job_postings")->name("job_postings.")->group(function(){
   Route::livewire('/', 'pages::job_postings.all')
        ->name('index');
        Route::livewire('/create', 'pages::job_postings.create')
        ->name('create');
           Route::livewire('/{id}/edit', 'pages::job_postings.edit')
        ->name('edit');
                 Route::livewire('/{id}', 'pages::job_postings.show')
        ->name('show');
});
Route::livewire("shifts","pages::shifts.all")->name("shifts.index");
Route::livewire("shifts/create","pages::shifts.create")->name("shifts.create");
Route::livewire("shifts/{id}/edit","pages::shifts.edit")->name("shifts.edit");
  Route::livewire('expensecategories', 'pages::expenses.expensecategories.all')
    ->name('expense-categories.index');
     Route::livewire('expensecategoriescreate', 'pages::expenses.expensecategories.create')
    ->name('expense-categories.create');
     Route::livewire('expensecategoriesshow/{id}', 'pages::expenses.expensecategories.show')
    ->name('expense-categories.show');
    Route::livewire('expensecategories/{id}/edit', 'pages::expenses.expensecategories.edit')
    ->name('expense-categories.edit');
});
Route::livewire("wallettoprequests","pages::wallettoprequest.all")->name("wallet-topups.index");
Route::livewire("wallettoprequests/{id}/edit","pages::wallettoprequest.edit")->name("wallet-topups.edit");
Route::livewire('customer_order/{order}', "pages::orders.detail")
    ->name('customer.orders.show');
Route::livewire("sales_return","pages::sales.sale_return.all")->name("sales_return.index");
Route::livewire("sales_return/{id}/edit","pages::sales.sale_return.edit")->name("sales_return.edit");
Route::livewire("sales_return/{id}/show","pages::sales.sale_return.show")->name("sales_return.show");

Route::livewire('/orders', 'pages::orders.all')->name('orders.index');
Route::livewire('/purchase-returns', 'pages::purchases.returns.all')->name('purchases.returns.index');
Route::livewire("/purchase-returns/create","pages::purchases.returns.create")->name("purchases.returns.create");
Route::livewire('/purchase-returns/{id}/show', 'pages::purchases.returns.show')->name('purchases.returns.show');
Route::livewire('/purchase-returns/{id}/edit', 'pages::purchases.returns.edit')->name('purchases.returns.edit');

     Route::livewire('orders/create', 'pages::orders.create')->name('orders.create');

Route::livewire('/orders/{id}/edit', 'pages::orders.edit')->name('orders.edit');
Route::livewire('/orders/{id}', 'pages::orders.show')->name('orders.show');
Route::get("/order/report",OrderController::class)->name("order.report");
Route::get("/order/{id}/report",OrderReportController::class)->name("order.invoice.report");
Route::livewire("customer-support-tickets","pages::tickets.all")->name('customer-support-tickets.index');
Route::livewire("customer-support-tickets/{id}/edit","pages::tickets.edit")->name('customer-support-tickets.edit');
Route::livewire("customer-support-tickets/{id}","pages::tickets.show")->name('customer-support-tickets.show');
Route::livewire('/settings', 'pages::settings.index')->name('settings.index');
/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
 Route::livewire('/ai-assistant', "pages::customer.mcp")->name('customer.ai.assistant');

 Route::livewire('/customer/ai', 'pages::customer.ai')
    ->name('customer.ai.chatbot');
//applicantportal.blade
 Route::livewire('/career', 'pages::frontend.career.index')
    ->name('career');
     Route::livewire(
    '/jobdetail/{id}',
    'pages::frontend.career.jobdetail'
)->name('jobdetail');
      Route::livewire('/applicantportal', 'pages::frontend.career.applicantportal')
    ->name('applicantportal');
      Route::livewire('/applicantauth', 'pages::frontend.career.auth')
    ->name('applicantauth');
 Route::get('/invoice/{order}', InvoiceController::class)
    ->name("invoice.download");

    Route::livewire("/checkout", "pages::frontend.checkout")->name("checkout")->middleware("customer"); 
Route::prefix('customer')->name('customer.')->group(function () {
    Route::livewire("/login", "pages::frontend.login")->name("login");
    Route::livewire("/register", "pages::frontend.register")->name("register");
    Route::livewire("/forget_password", "pages::frontend.forget_password")->name("forget_password");
    Route::livewire("/reset_password/{token}", "pages::frontend.reset_password")->name("password_reset");
  Route::livewire('/contact-us', 'pages::frontend.customer.contact-us')->name('contact-us');
    Route::middleware('customer')->group(function () {
        Route::livewire('/dashboard', 'pages::frontend.customer.dashboard')->name('dashboard');
       
        Route::livewire('/orders', 'pages::frontend.customer.orders')->name('orders');
        Route::livewire('/orders/{id}', 'pages::frontend.customer.order-detail')->name('order.detail');
        Route::livewire('/addresses', 'pages::frontend.customer.addresses')->name('addresses');
        Route::livewire('/returns', 'pages::frontend.customer.returns')->name('returns');
        Route::livewire('/wallet', 'pages::frontend.customer.wallet')->name('wallet');
        Route::livewire("/wallet/add", "pages::frontend.customer.wallet.add")->name("wallet.add");
        Route::livewire('/profile', 'pages::frontend.customer.profile')->name('profile');
     
        Route::livewire('/support-ticket', 'pages::frontend.customer.support-ticket')->name('support.ticket');
        Route::livewire('/my-support-tickets', 'pages::frontend.customer.my-support-ticket')->name('my.support.tickets');
        Route::livewire('/support-tickets/{ticketNo}', 'pages::frontend.customer.ticket-detail')->name('ticket.detail');
        Route::livewire('/wishlist', 'pages::frontend.customer.wishlist')->name('wishlist');
        Route::get("/logout",function(){
              auth("customer")->logout();
              return redirect()->route("customer.login");
        })->name('logout');
    });
});