<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissonAfkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Bersihkan cache Spatie sebelum mulai
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Kelompokkan Permission Baru berdasarkan View & Action
        $modules = [
            'Inventory & Produksi' => [
                'view' => [
                    'inventory.kartu-stok',
                ],
                'action' => [
                    'inventory.cetak-kartu-stok',
                    'inventory.afkir-ulang',
                    'inventory.tutup-buku',
                ]
            ],
            'Transaksi' => [
                'view' => [],
                'action' => [
                    'barang-keluar.afkir-ulang',
                ]
            ]
        ];

        // 3. Eksekusi pembuatan Permission ke Database
        foreach ($modules as $moduleName => $types) {
            foreach ($types as $type => $permissions) {
                foreach ($permissions as $permission) {
                    // Buat permission jika belum ada di database
                    Permission::findOrCreate($permission, 'web');
                }
            }
        }

        // 4. Bersihkan cache lagi setelah permission dibuat agar langsung terbaca oleh sistem
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
