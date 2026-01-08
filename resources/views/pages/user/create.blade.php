<x-layout.user.app>
    <div class="py-2">
        <form action="{{ route('user.store') }}" method="POST"
            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
            @csrf

            <div class="p-6 md:p-8 space-y-8">
                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                        <div class="p-2 bg-green-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Tambah User Baru</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        {{-- Nama Lengkap --}}
                        <div class="space-y-1">
                            <label for="name" class="block text-sm font-semibold text-gray-700">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all border outline-none">
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        {{-- Username --}}
                        <div class="space-y-1">
                            <label for="username" class="block text-sm font-semibold text-gray-700">Username <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="username" name="username" required value="{{ old('username') }}"
                                placeholder="Masukkan username"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all border outline-none uppercase">
                            @error('username')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Perusahaan --}}
                        <div class="space-y-1">
                            <label for="id_perusahaan"
                                class="block text-sm font-semibold text-gray-700">Perusahaan</label>
                            <select id="id_perusahaan" name="id_perusahaan"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all border bg-white cursor-pointer outline-none">
                                <option value="" disabled selected>-- Pilih Perusahaan --</option>
                                @foreach ($perusahaan as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('id_perusahaan') == $p->id ? 'selected' : '' }}>{{ $p->nama_perusahaan }} ({{ $p->kota }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_perusahaan')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Role (Spatie) --}}
                        <div class="space-y-1">
                            <label for="role" class="block text-sm font-semibold text-gray-700">Role Akses <span
                                    class="text-red-500">*</span></label>
                            <select id="role" name="role" required
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all border bg-white cursor-pointer outline-none">
                                <option value="" disabled selected>-- Pilih Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="space-y-1">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Password <span
                                    class="text-red-500">*</span></label>
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
                                class="block text-sm font-semibold text-gray-700">Konfirmasi Password <span
                                    class="text-red-500">*</span></label>
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
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-2.5 text-sm font-bold text-white bg-green-500 hover:bg-green-600 rounded-xl transition-all active:scale-95 shadow-sm">
                        Simpan User
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
