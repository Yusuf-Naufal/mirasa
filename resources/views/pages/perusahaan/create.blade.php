<x-layout.user.app>
    <div class="py-2">

        <form action="{{ route('perusahaan.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
            @csrf

            <div class="p-6 md:p-8 space-y-8">

                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                        <div class="p-2 bg-amber-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#FFC829]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Detail Perusahaan</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-1">
                            <label for="nama_perusahaan" class="block text-sm font-semibold text-gray-700">
                                Nama Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_perusahaan" name="nama_perusahaan" required
                                placeholder="Masukkan nama resmi perusahaan"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border">
                        </div>

                        <div class="space-y-1">
                            <label for="kontak" class="block text-sm font-semibold text-gray-700">
                                Nomor Kontak <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm font-semibold">
                                    +62
                                </span>
                                <input type="tel" id="kontak" name="kontak" required placeholder="812345678"
                                    inputmode="numeric" pattern="[0-9]*"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    class="w-full rounded-r-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border">
                            </div>
                        </div>

                        <div class="space-y-1 md:col-span-2 lg:col-span-1">
                            <label for="jenis_perusahaan" class="block text-sm font-semibold text-gray-700">
                                Jenis Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <select id="jenis_perusahaan" name="jenis_perusahaan" required
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border bg-white appearance-none cursor-pointer">
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                <option value="Pusat">Kantor Pusat</option>
                                <option value="Cabang">Kantor Cabang</option>
                                <option value="Anak Perusahaan">Anak Perusahaan</option>
                            </select>
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <label for="alamat" class="block text-sm font-semibold text-gray-700">Alamat Lengkap<span
                                    class="text-red-500">*</span></label>
                            <textarea id="alamat" name="alamat" rows="3" placeholder="Jalan, No. Bangunan, Kota, dsb."
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border resize-none"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 italic">* Wajib diisi</p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('perusahaan.index') }}"
                        class="flex-1 sm:flex-none text-center px-6 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-800 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-2.5 text-sm font-bold text-white bg-green-500 hover:bg-green-600 rounded-xl transition-all active:scale-95 shadow-sm">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layout.user.app>
