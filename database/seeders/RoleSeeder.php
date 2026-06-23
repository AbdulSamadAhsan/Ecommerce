<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
           Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Customer']);
        Role::create(['name' => 'Employee']);
        Role::create(['name' => 'Manager']);
        Role::create(['name' => 'Supplier']);
        Role::create(['name' => 'WarehouseManager']);
        Role::create(['name' => 'SalesManager']);
        Role::create(['name' => 'HRManager']);
        Role::create(['name' => 'Accountant']);
        Role::create(['name' => 'DeliveryBoy']);


    }
}