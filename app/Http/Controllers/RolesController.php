<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);

        return view('pages.user.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('pages.user.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Ubah input name menjadi kapital semua sebelum validasi
        $request->merge([
            'name' => ucwords(strtolower($request->name))
        ]);

        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Menggunakan $request->name yang sudah di-merge menjadi kapital
                $role = Role::create([
                    'name' => $request->name,
                    'guard_name' => 'web'
                ]);

                if ($request->filled('permissions')) {
                    $role->syncPermissions($request->permissions);
                }
            });

            return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat role: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('pages.user.role.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // 1. Ubah input name menjadi kapital semua sebelum validasi
        $request->merge([
            'name' => ucwords(strtolower($request->name))
        ]);

        $request->validate([
            // Pastikan pengecekan unique mengecualikan ID role saat ini
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        try {
            DB::transaction(function () use ($request, $role) {
                // Update menggunakan name yang sudah kapital
                $role->update(['name' => $request->name]);

                // Jika permissions kosong, kirim array kosong agar izin lama terhapus (Revoke)
                $role->syncPermissions($request->permissions ?? []);
            });

            return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
        } catch (\Exception $e) {
            // Sebaiknya log error atau tampilkan pesan spesifik jika perlu
            return back()->with('error', 'Gagal memperbarui role: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Role Super Admin tidak boleh dihapus.');
        }

        $guestRole = Role::firstOrCreate(['name' => 'Guest', 'guard_name' => 'web']);

        $users = $role->users;
        foreach ($users as $user) {
            $user->assignRole($guestRole);
        }

        $role->syncPermissions([]);

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role dan user dipindahkan ke Guest.');
    }
}
