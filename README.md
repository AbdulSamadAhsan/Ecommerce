<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
# Ecommerce ERP CRM Inventory System

A comprehensive business management platform built with Laravel and Livewire that combines E-Commerce, Inventory Management, ERP, CRM, POS, and Warehouse Management into a single application.

## Overview

This project is designed for small and medium-sized businesses that need a centralized system to manage products, customers, sales, inventory, suppliers, employees, and business operations efficiently.

## Features

### Inventory Management

* Product Management
* Category Management
* Brand Management
* Stock Tracking
* Low Stock Alerts
* Warehouse Management
* Inventory Reports

### Customer Relationship Management (CRM)

* Customer Management
* Customer Purchase History
* Customer Analytics
* Customer Profiles

### Enterprise Resource Planning (ERP)

* Employee Management
* Department Management
* Role Management
* Business Reports
* Revenue Tracking

### Sales & POS

* Sales Management
* Order Processing
* Invoice Generation
* Payment Tracking
* Sales Reports

### E-Commerce

* Product Catalog
* Shopping Cart
* Checkout Process
* Customer Authentication
* Order Tracking

## Technology Stack

| Technology | Version |
| ---------- | ------- |
| PHP        | 8.3+    |
| Laravel    | 12      |
| Livewire   | 4       |
| MySQL      | 8+      |
| Bootstrap  | 5       |
| Chart.js   | Latest  |

## Project Modules

### User Management

* Users
* Roles
* Permissions
* Authentication

### Product Management

* Products
* Categories
* Brands
* Suppliers

### Inventory

* Warehouses
* Stock Movements
* Inventory Tracking

### Customer Management

* Customers
* Customer Orders
* Customer Reports

### Sales

* Sales
* Sale Items
* Orders
* Payments

### Human Resources

* Employees
* Departments
* Managers

## Installation

### Clone Repository

```bash
git clone https://github.com/AbdulSamadAhsan/Ecommerce.git

cd Ecommerce
```
 if i need i add these 
 reward_points
reward_transactions
referrals
referral_rewards
referral_tiers
### Install Dependencies

```bash
composer install

npm install
```

### Environment Setup

```bash
cp .env.example .env

php artisan key:generate
```

### Database Configuration

Update your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

### Run Migrations

```bash
php artisan migrate
```

### Build Assets

```bash
npm run build
```

### Start Development Server

```bash
php artisan serve
```

## Database Structure

Core tables include:

* users
* roles
* employees
* departments
* customers
* suppliers
* warehouses
* categories
* brands
* products
* sales
* sale_items
* orders

## Future Roadmap

* REST API Development
* Mobile Application
* Barcode Support
* QR Code Integration
* Multi-Warehouse Inventory
* AI Analytics Dashboard
* Email Notifications
* WhatsApp Integration
* Multi-Vendor Support

## Security Features

* CSRF Protection
* Authentication
* Authorization
* Password Hashing
* Input Validation
* Database Foreign Keys

## Contributing

Contributions are welcome.

1. Fork the repository.
2. Create a feature branch.
3. Commit your changes.
4. Push the branch.
5. Create a Pull Request.

## License

This project is licensed under the MIT License.

## Author

Syed Abdul Samad Ahsan

Laravel Developer | ERP Developer | CRM Developer | Inventory Management Systems


