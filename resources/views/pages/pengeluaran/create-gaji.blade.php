<x-layout.beranda.app title="Tambah Pengeluaran Kesejahteraan">
    <div class="md:px-10 py-6 flex flex-col" x-data="{ menuOpen: false }">
        <div class="flex-1 pt-12">

            {{-- 1. HEADER --}}
            <div class="mb-4 text-left">
                <a href="{{ route('pengeluaran.index') }}"
                    class="group text-blue-600 hover:text-blue-700 text-sm font-semibold inline-flex items-center gap-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mt-2">Pengeluaran <span
                        class="text-purple-600">Kesejahteraan</span></h1>
                <p class="text-sm text-gray-500 font-medium italic">*Gunakan kategori ini untuk kesejahteraan karyawan perusahaan.</p>
            </div>

            <form action="{{ route('pengeluaran.store') }}" method="POST" enctype="multipart/form-data" class="form-prevent-multiple-submits">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 md:px-0">

                    {{-- LEFT SIDE: FORM INPUT --}}
                    <div class="lg:col-span-2 space-y-6 text-left">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                {{-- Hidden Kategori --}}
                                <input type="hidden" name="kategori" value="KESEJAHTERAAN">

                                <div class="md:col-span-2 text-left">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama
                                        Pengeluaran</label>
                                    <input type="text" name="nama_pengeluaran"
                                        placeholder="Contoh: Gaji Karyawan, Konsultasi"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                        required>
                                </div>

                                <div class="text-left">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis
                                        Barang/Jasa</label>
                                    <input type="text" name="sub_kategori" id="sub_kategori"
                                        placeholder="Contoh: GAJI, KONSULTASI, BONUS"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all uppercase"
                                        required>
                                </div>

                                <div class="text-left">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Bayar</label>
                                    <input type="date" name="tanggal_pengeluaran" value="{{ date('Y-m-d') }}"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                </div>

                                <div class="md:col-span-2 text-left">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Total Nominal
                                        (Rp)</label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400">Rp</span>
                                        <input type="number" name="jumlah_pengeluaran" placeholder="0"
                                            class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 focus:bg-white focus:border-blue-500 focus:ring-0 outline-none text-2xl font-bold transition-all uppercase"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 text-left">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="4" placeholder="Detail pembelian atau keperluan..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all"></textarea>
                        </div>
                    </div>

                    {{-- RIGHT SIDE: SETTINGS & UPLOAD --}}
                    <div class="space-y-6 text-left">

                        {{-- KLASIFIKASI BEBAN (Default Non-HPP untuk Office) --}}
                        <div class="md:col-span-2 bg-blue-50/50 rounded-xl p-4 border border-blue-100">
                            <label class="block text-sm font-bold text-blue-900 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Klasifikasi Beban Biaya
                            </label>
                            <div class="flex flex-wrap gap-4">
                                <label class="flex-1 cursor-pointer group">
                                    <input type="radio" name="is_hpp" value="1" id="radio_hpp"
                                        class="peer hidden" checked>
                                    <div
                                        class="p-3 bg-white border-2 border-gray-200 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all group-hover:border-blue-300">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="text-sm font-bold text-gray-700 peer-checked:text-blue-700">Masuk
                                                HPP</span>
                                            <div
                                                class="w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-blue-500 flex items-center justify-center">
                                                <div class="w-2 h-2 rounded-full bg-blue-500 hidden peer-checked:block">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-gray-500 mt-1">Biaya akan dibebankan untuk menghitung
                                            HPP</p>
                                    </div>
                                </label>

                                <label class="flex-1 cursor-pointer group">
                                    <input type="radio" name="is_hpp" value="0" id="radio_non_hpp"
                                        class="peer hidden" >
                                    <div
                                        class="p-3 bg-white border-2 border-gray-200 rounded-xl peer-checked:border-gray-500 peer-checked:bg-gray-50 transition-all group-hover:border-gray-300">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="text-sm font-bold text-gray-700 peer-checked:text-gray-900">Non-HPP</span>
                                            <div
                                                class="w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-gray-500 flex items-center justify-center">
                                                <div class="w-2 h-2 rounded-full bg-gray-600 hidden peer-checked:block">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-gray-500 mt-1">Biaya pengeluaran tidak di bebankan ke
                                            HPP</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- PREVIEW UPLOAD --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-left">
                            <label class="block text-sm font-semibold text-gray-700 mb-4 text-center italic">
                                Upload Bukti Pembayaran (PDF, JPG, PNG)
                            </label>

                            <div class="relative group">
                                <div id="preview-container"
                                    class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-xl p-6 hover:border-blue-400 transition-all cursor-pointer min-h-[200px] bg-gray-50/50">

                                    <div id="placeholder-content" class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 group-hover:text-blue-500 mb-3"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <span class="text-sm text-gray-500 font-medium text-center">Tarik file atau
                                            klik</span>
                                        <span class="text-xs text-gray-400 mt-1">Maksimal 2MB</span>
                                    </div>

                                    <img id="image-preview"
                                        class="hidden w-full h-auto max-h-48 object-contain rounded-lg shadow-sm mb-2">

                                    <div id="pdf-preview" class="hidden flex flex-col items-center">
                                        <svg class="w-16 h-16 text-red-500 mb-2" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z">
                                            </path>
                                            <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"></path>
                                        </svg>
                                        <span id="pdf-name"
                                            class="text-xs font-semibold text-gray-600 truncate max-w-[150px]">nama-file.pdf</span>
                                    </div>

                                    <input type="file" name="bukti" id="bukti-input"
                                        accept="image/*,application/pdf"
                                        class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>

                                <button type="button" id="reset-preview"
                                    class="hidden absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="flex flex-col gap-3 pt-4">
                            <button type="submit"
                                class="btn-submit w-full bg-purple-700 hover:bg-purple-800 text-white font-bold py-4 rounded-xl shadow-lg transition-all flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="btn-text">Simpan Pengeluaran</span>
                                <svg class="btn-spinner hidden animate-spin ml-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </button>
                            <a href="{{ route('pengeluaran.index') }}"
                                class="w-full bg-white border border-gray-200 text-gray-600 text-center py-4 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        // Auto Capitalize Sub Kategori
        document.getElementById('sub_kategori').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Handle Preview Bukti
        const input = document.getElementById('bukti-input');
        const imagePreview = document.getElementById('image-preview');
        const pdfPreview = document.getElementById('pdf-preview');
        const pdfName = document.getElementById('pdf-name');
        const placeholder = document.getElementById('placeholder-content');
        const resetBtn = document.getElementById('reset-preview');
        const dropzone = document.getElementById('preview-container');

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                resetBtn.classList.remove('hidden');
                placeholder.classList.add('hidden');

                if (file.type.startsWith('image/')) {
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        pdfPreview.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    imagePreview.classList.add('hidden');
                    pdfPreview.classList.remove('hidden');
                    pdfName.textContent = file.name;
                }
            }
        });

        resetBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            input.value = '';
            imagePreview.classList.add('hidden');
            pdfPreview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            resetBtn.classList.add('hidden');
        });

        dropzone.addEventListener('dragover', () => dropzone.classList.add('border-blue-400', 'bg-blue-50'));
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('border-blue-400', 'bg-blue-50'));
        dropzone.addEventListener('drop', () => dropzone.classList.remove('border-blue-400', 'bg-blue-50'));
    </script>
</x-layout.beranda.app>
