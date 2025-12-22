<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil data perusahaan dan jenis untuk isi dropdown filter di view
        $perusahaan = Perusahaan::all();
        $role = Role::all();

        // Query dasar dengan eager loading relasi
        $query = User::with(['perusahaan']);

        // Filter berdasarkan Search (Nama Barang atau Kode)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(username) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(name) like ?', ["%{$search}%"]);
            });
        }

        // Filter berdasarkan Perusahaan
        if ($request->filled('id_perusahaan')) {
            $query->where('id_perusahaan', $request->id_perusahaan);
        }

        // Order by (Sesuai permintaan) dan paginate
        // appends(request()->query()) memastikan filter tidak hilang saat ganti halaman
        $user = $query->orderBy('id_perusahaan', 'asc')->paginate(10)->withQueryString();

        return view('pages.user.index', compact('user', 'perusahaan', 'role'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $perusahaan = Perusahaan::whereNull('deleted_at')->get();
        $roles = Role::get();

        return view('pages.user.create', compact('perusahaan', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name'          => 'required|string|max:255',
            'id_perusahaan' => 'nullable|exists:perusahaan,id',
            'username'      => 'required|string|max:50|unique:users,username',
            'password'      => 'required|string|min:8|confirmed',
            'role'          => 'required|exists:roles,name',
        ]);

        try {
            // Gunakan Database Transaction untuk memastikan data user dan role tersimpan bersamaan
            DB::beginTransaction();

            // 2. Simpan Data User
            $user = User::create([
                'name'          => $request->name,
                'id_perusahaan' => $request->id_perusahaan,
                'username'      => strtoupper($request->username),
                'password'      => Hash::make($request->password),
            ]);

            // 3. Assign Role Spatie
            $user->assignRole($request->role);

            DB::commit();

            return redirect()->route('user.index')
                ->with('success', 'User ' . $user->name . ' berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
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
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $perusahaan = Perusahaan::whereNull('deleted_at')->get();
        $roles = Role::get();

        return view('pages.user.edit', compact('perusahaan', 'roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // 1. Validasi Input
        $request->validate([
            'name'          => 'required|string|max:255',
            'id_perusahaan' => 'nullable|exists:perusahaan,id',
            'username'      => 'required|string|max:50|unique:users,username,' . $user->id,
            'password'      => 'nullable|string|min:8|confirmed',
            'role'          => 'required|exists:roles,name',
        ]);

        try {
            DB::beginTransaction();

            // if (auth()->id() === $user->id && $request->role !== $user->roles->first()->name) {
            //     return back()->withErrors(['role' => 'Anda tidak diperbolehkan mengubah Role Anda sendiri.']);
            // }

            // 2. Persiapkan Data Update
            $data = [
                'name'          => $request->name,
                'id_perusahaan' => $request->id_perusahaan,
                'username'      => strtoupper($request->username), // Paksa ke UPPERCASE
            ];

            // 3. Logika Update Password (hanya jika diisi)
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // 4. Update Database
            $user->update($data);

            // 5. Sinkronisasi Role Spatie (Menghapus role lama & mengganti ke yang baru)
            $user->syncRoles($request->role);

            DB::commit();

            return redirect()->route('user.index')
                ->with('success', 'Data user ' . $user->name . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // 1. Validasi Keamanan: Cegah admin menghapus dirinya sendiri
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak diperbolehkan menghapus akun yang sedang digunakan.');
        }

        try {
            DB::beginTransaction();

            // 2. Hapus User
            $user->delete();

            DB::commit();

            return redirect()->route('user.index')
                ->with('success', 'User ' . $user->name . ' telah berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
