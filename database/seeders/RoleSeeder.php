<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'KA Kupas']);
        Role::firstOrCreate(['name' => 'QC']);
        Role::firstOrCreate(['name' => 'Admin Gudang']);
        Role::firstOrCreate(['name' => 'Admin Kantor']);
        Role::firstOrCreate(['name' => 'Kepala Produksi']);
        Role::firstOrCreate(['name' => 'Manager']);
    }
}
