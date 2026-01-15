<x-layout.user.app title="Edit Pengguna">>
    <div class="py-2">
        {{-- Gunakan method PUT untuk update data --}}
        <form action="{{ route('user.update', $user->id) }}" method="POST"
            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
            @csrf
            @method('PUT')

            <div class="p-6 md:p-8 space-y-8">
                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                        <div class="p-2 bg-amber-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M4 21q-.425 0-.712-.288T3 20v-2.425q0-.4.15-.763t.425-.637L16.2 3.575q.3-.275.663-.425t.762-.15t.775.15t.65.45L20.425 5q.3.275.437.65T21 6.4q0 .4-.138.763t-.437.662l-12.6 12.6q-.275.275-.638.425t-.762.15zM17.6 7.8L19 6.4L17.6 5l-1.4 1.4z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Edit Data User</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        {{-- Nama Lengkap --}}
                        <div class="space-y-1">
                            <label for="name" class="block text-sm font-semibold text-gray-700">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" required
                                value="{{ old('name', $user->name) }}"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all border outline-none">
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Username --}}
                        <div class="space-y-1">
                            <label for="username" class="block text-sm font-semibold text-gray-700">Username <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="username" name="username" required
                                value="{{ old('username', $user->username) }}"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all border outline-none uppercase">
                            @error('username')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Perusahaan --}}
                        <div class="space-y-1">
                            <label for="id_perusahaan" class="block text-sm font-semibold text-gray-700">Perusahaan</label>
                            <select id="id_perusahaan" name="id_perusahaan"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all border bg-white cursor-pointer outline-none">
                                @foreach ($perusahaan as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('id_perusahaan', $user->id_perusahaan) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_perusahaan }} ({{ $p->kota }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Role (Spatie) --}}
                        <div class="space-y-1">
                            <label for="role" class="block text-sm font-semibold text-gray-700">Role Akses <span
                                    class="text-red-500">*</span></label>
                            <select id="role" name="role" required
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all border bg-white cursor-pointer outline-none">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ (old('role') ?? ($user->roles->first()->name ?? '')) == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Password --}}
                        <div class="space-y-1">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Password Baru <span
                                    class="text-gray-400 font-normal text-xs">(Kosongkan jika tidak
                                    diubah)</span></label>
                            <div class="relative">
                                <input type="password" id="password" name="password" placeholder="••••••••"
                                    class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all border outline-none pr-12">
                                <button type="button" onclick="togglePassword('password', 'eye-icon-1')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-amber-500 transition-colors">
                                    <svg id="eye-icon-1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="space-y-1">
                            <label for="password_confirmation"
                                class="block text-sm font-semibold text-gray-700">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="••••••••"
                                    class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all border outline-none pr-12">
                                <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-amber-500 transition-colors">
                                    <svg id="eye-icon-2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between gap-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 italic text-start">* Wajib diisi</p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('user.index') }}"
                        class="flex-1 sm:flex-none text-center border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-600 rounded-xl hover:text-gray-800 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-2.5 text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-xl transition-all active:scale-95 shadow-sm">
                        Update User
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Script untuk Toggle Password --}}
    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Ubah SVG menjadi Eye Off
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                passwordInput.type = 'password';
                // Ubah SVG kembali menjadi Eye Normal
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</x-layout.user.app>
