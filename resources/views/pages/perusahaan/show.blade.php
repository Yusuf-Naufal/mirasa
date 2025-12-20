<x-layout.user.app>
    <div class="py-2">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Perusahaan</h1>
                <p class="text-sm text-gray-500">Detail informasi untuk
                    <strong>{{ $perusahaan->nama_perusahaan }}</strong></p>
            </div>
        </div>

        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-8">
                <div class="flex items-center">
                    <div
                        class="h-20 w-20 bg-white rounded-full flex items-center justify-center text-blue-600 text-3xl font-bold">
                        {{ strtoupper(substr($perusahaan->nama_perusahaan, 0, 1)) }}
                    </div>
                    <div class="ml-6 text-white">
                        <h1 class="text-3xl font-bold">{{ $perusahaan->nama_perusahaan }}</h1>
                        <p class="opacity-80">ID Perusahaan: #{{ $perusahaan->id }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-500 block">Nomor Kontak / Telepon</label>
                            <p class="text-gray-900 font-medium">{{ $perusahaan->kontak ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 block">Alamat Kantor</label>
                            <p class="text-gray-900 leading-relaxed">
                                {{ $perusahaan->alamat ?? 'Alamat belum diisi.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Log Sistem</h3>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ditambahkan pada:</span>
                            <span class="text-gray-900">{{ $perusahaan->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Pembaruan Terakhir:</span>
                            <span class="text-gray-900">{{ $perusahaan->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-3">
                        <a href="{{ route('perusahaan.edit', $perusahaan->id) }}"
                            class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 rounded-lg transition">
                            Edit Data
                        </a>
                        <button onclick="confirmDelete()"
                            class="flex-1 bg-red-100 text-red-600 hover:bg-red-200 py-2 rounded-lg transition">
                            Hapus Perusahaan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.user.app>
