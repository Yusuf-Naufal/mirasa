<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat atau Temukan Role Super Admin
        $superAdminRole = Role::findOrCreate('Super Admin', 'web');

        // 2. Ambil Semua Permission yang ada di database
        $allPermissions = Permission::all();

        // 3. Berikan Semua Permission ke Role Super Admin
        $superAdminRole->syncPermissions($allPermissions);

    }
}
