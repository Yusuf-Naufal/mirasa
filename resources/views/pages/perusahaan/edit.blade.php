<x-layout.user.app>
    <div class="py-2">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Perusahaan</h1>
                <p class="text-sm text-gray-500">Ubah informasi untuk <strong>{{ $perusahaan->nama_perusahaan }}</strong></p>
            </div>
        </div>

        <form action="{{ route('perusahaan.update', $perusahaan->id) }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
            @csrf
            @method('PUT')

            <div class="p-6 md:p-8 space-y-8">
                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.5 2.5 0 113.536 3.536L12 14.232l-4 1 1-4 9.732-9.732z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Perbarui Detail Perusahaan</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-1">
                            <label for="nama_perusahaan" class="block text-sm font-semibold text-gray-700">
                                Nama Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_perusahaan" name="nama_perusahaan" 
                                value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}" required
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-blue-500 transition-colors border">
                        </div>

                        <div class="space-y-1">
                            <label for="kontak" class="block text-sm font-semibold text-gray-700">
                                Nomor Kontak <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm font-semibold">
                                    +62
                                </span>
                                <input type="tel" id="kontak" name="kontak" 
                                    value="{{ old('kontak', $perusahaan->kontak) }}" required
                                    inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '')"
                                    class="w-full rounded-r-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-blue-500 transition-colors border">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="jenis_perusahaan" class="block text-sm font-semibold text-gray-700">
                                Jenis Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <select id="jenis_perusahaan" name="jenis_perusahaan" required
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:outline-none focus:border-blue-500 transition-colors border bg-white cursor-pointer appearance-none">
                                <option value="Pusat" {{ old('jenis_perusahaan', $perusahaan->jenis_perusahaan) == 'Pusat' ? 'selected' : '' }}>Kantor Pusat</option>
                                <option value="Cabang" {{ old('jenis_perusahaan', $perusahaan->jenis_perusahaan) == 'Cabang' ? 'selected' : '' }}>Kantor Cabang</option>
                                <option value="Anak Perusahaan" {{ old('jenis_perusahaan', $perusahaan->jenis_perusahaan) == 'Anak Perusahaan' ? 'selected' : '' }}>Anak Perusahaan</option>
                            </select>
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <label for="alamat" class="block text-sm font-semibold text-gray-700">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea id="alamat" name="alamat" rows="3" required
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-blue-500 transition-colors border resize-none">{{ old('alamat', $perusahaan->alamat) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">Terakhir diperbarui: {{ $perusahaan->updated_at->format('d M Y, H:i') }}</p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('perusahaan.index') }}"
                        class="flex-1 sm:flex-none text-center px-6 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-800 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-2.5 text-sm font-bold text-white bg-yellow-600 hover:bg-yellow-700 rounded-xl transition-all active:scale-95">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layout.user.app>