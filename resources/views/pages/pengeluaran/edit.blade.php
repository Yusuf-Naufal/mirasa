<x-layout.beranda.app :title="'Edit Pengeluaran ' . $pengeluaran->kategori">
    @php
        // Konfigurasi Dinamis berdasarkan Kategori
        $config = [
            'ADMINISTRASI' => ['color' => 'orange', 'icon' => 'clip'],
            'KESEJAHTERAAN' => ['color' => 'purple', 'icon' => 'heart'],
            'LIMBAH' => ['color' => 'green', 'icon' => 'trash'],
            'MAINTENANCE' => ['color' => 'amber', 'options' => ['PERBAIKAN', 'SEWA']],
            'OFFICE' => ['color' => 'slate', 'options' => ['GALON', 'ATK', 'KERTAS', 'FOTOCOPY']],
            'OPERASIONAL' => ['color' => 'blue', 'options' => ['GAS', 'LISTRIK', 'TELEPON', 'INTERNET', 'AIR (PDAM)']],
        ];

        $currentConfig = $config[$pengeluaran->kategori] ?? $config['OPERASIONAL'];
        $color = $currentConfig['color'];
        $options = $currentConfig['options'] ?? null;

        // Cek apakah sub_kategori saat ini ada di dalam opsi default
        $isCustom = $options && !in_array($pengeluaran->sub_kategori, $options);
    @endphp

    <div class="min-h-screen bg-gray-50/50 md:px-10 py-8">
        <div class="mx-auto flex flex-col pt-12">

            {{-- HEADER --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <a href="{{ route('pengeluaran.index') }}"
                        class="group inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-semibold transition-all mb-2">
                        <svg class="h-4 w-4 mr-1 transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit <span
                            class="text-{{ $color }}-600">{{ $pengeluaran->kategori }}</span></h1>
                    <p class="text-sm text-gray-500 font-medium italic">*Perbarui data pengeluaran yang diperlukan.</p>
                </div>
            </div>

            <form action="{{ route('pengeluaran.update', $pengeluaran->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 md:px-0">

                    {{-- LEFT SIDE: FORM INPUT --}}
                    <div class="lg:col-span-2 space-y-6 text-left">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <input type="hidden" name="kategori" value="{{ $pengeluaran->kategori }}">

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama
                                        Pengeluaran</label>
                                    <input type="text" name="nama_pengeluaran"
                                        value="{{ $pengeluaran->nama_pengeluaran }}"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-{{ $color }}-500 outline-none transition-all"
                                        required>
                                </div>

                                {{-- DINAMIS SUB-KATEGORI (Dropdown vs Input) --}}
                                @if ($options)
                                    <div x-data="{ selectedLayanan: '{{ $isCustom ? 'LAINNYA' : $pengeluaran->sub_kategori }}', customLayanan: '{{ $isCustom ? $pengeluaran->sub_kategori : '' }}' }">
                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis
                                                Layanan</label>
                                            <select :name="selectedLayanan !== 'LAINNYA' ? 'sub_kategori' : ''"
                                                x-model="selectedLayanan"
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none bg-white appearance-none transition-all"
                                                required>
                                                <option value="" disabled>-- Pilih Jenis Layanan --</option>
                                                @foreach ($options as $opt)
                                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                                @endforeach
                                                <option value="LAINNYA">LAINNYA...</option>
                                            </select>
                                        </div>

                                        <div x-show="selectedLayanan === 'LAINNYA'" class="mt-3">
                                            <label
                                                class="block text-sm font-semibold text-gray-400 mb-2 uppercase">Sebutkan
                                                Layanan</label>
                                            <input type="text"
                                                :name="selectedLayanan === 'LAINNYA' ? 'sub_kategori' : ''"
                                                x-model="customLayanan"
                                                class="w-full px-4 py-3 rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-500 outline-none uppercase bg-blue-50/30 transition-all"
                                                :required="selectedLayanan === 'LAINNYA'">
                                        </div>
                                    </div>
                                @else
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis
                                            Barang/Jasa</label>
                                        <input type="text" name="sub_kategori" id="sub_kategori"
                                            value="{{ $pengeluaran->sub_kategori }}"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-{{ $color }}-500 outline-none transition-all uppercase"
                                            required>
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Bayar</label>
                                    <input type="date" name="tanggal_pengeluaran"
                                        value="{{ \Carbon\Carbon::parse($pengeluaran->tanggal_pengeluaran)->format('Y-m-d') }}"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Total Nominal
                                        (Rp)</label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400">Rp</span>
                                        <input type="number" name="jumlah_pengeluaran"
                                            value="{{ $pengeluaran->jumlah_pengeluaran }}"
                                            class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 focus:bg-white focus:border-blue-500 outline-none text-2xl font-bold"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="4"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">{{ $pengeluaran->keterangan }}</textarea>
                        </div>
                    </div>

                    {{-- RIGHT SIDE: SETTINGS & UPLOAD --}}
                    <div class="space-y-6 text-left">

                        {{-- HPP CLASSIFICATION --}}
                        <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100">
                            <label class="block text-sm font-bold text-blue-900 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Klasifikasi Beban Biaya
                            </label>
                            <div class="flex flex-wrap gap-4">
                                {{-- Opsi: Masuk HPP (Value 1) --}}
                                <label class="flex-1 cursor-pointer group">
                                    <input type="radio" name="is_hpp" value="1" id="radio_hpp"
                                        class="peer hidden" {{ $pengeluaran->is_hpp ? 'checked' : '' }}>
                                    {{-- BENAR: Jika is_hpp bernilai true/1 --}}
                                    <div
                                        class="p-3 bg-white border-2 border-gray-200 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all group-hover:border-blue-300">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-bold text-gray-700 peer-checked:text-blue-700">
                                                Masuk HPP
                                            </span>
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

                                {{-- Opsi: Non-HPP (Value 0) --}}
                                <label class="flex-1 cursor-pointer group">
                                    <input type="radio" name="is_hpp" value="0" id="radio_non_hpp"
                                        class="peer hidden" {{ !$pengeluaran->is_hpp ? 'checked' : '' }}>
                                    {{-- BENAR: Jika is_hpp bernilai false/0 --}}
                                    <div
                                        class="p-3 bg-white border-2 border-gray-200 rounded-xl peer-checked:border-gray-500 peer-checked:bg-gray-50 transition-all group-hover:border-gray-300">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-bold text-gray-700 peer-checked:text-gray-900">
                                                Non-HPP
                                            </span>
                                            <div
                                                class="w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-gray-500 flex items-center justify-center">
                                                <div
                                                    class="w-2 h-2 rounded-full bg-gray-600 hidden peer-checked:block">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-gray-500 mt-1">Biaya pengeluaran tidak dibebankan ke
                                            HPP</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- UPLOAD SECTION --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-4 text-center italic">Bukti
                                Pembayaran (Baru/Lama)</label>
                            <div class="relative group" id="dropzone">
                                <div id="preview-container"
                                    class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-xl p-6 min-h-[200px] bg-gray-50/50">

                                    @if ($pengeluaran->bukti)
                                        @if (Str::endsWith($pengeluaran->bukti, '.pdf'))
                                            <div id="initial-preview" class="flex flex-col items-center">
                                                <svg class="w-16 h-16 text-red-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z">
                                                    </path>
                                                </svg>
                                                <span class="text-xs font-bold text-gray-500">Lihat PDF Lama</span>
                                            </div>
                                        @else
                                            <img src="{{ asset('storage/' . $pengeluaran->bukti) }}"
                                                id="initial-preview"
                                                class="w-full h-auto max-h-48 object-contain rounded-lg shadow-sm">
                                        @endif
                                    @endif

                                    <div id="placeholder-content"
                                        class="{{ $pengeluaran->bukti ? 'hidden' : '' }} flex flex-col items-center text-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <span class="text-xs text-gray-500">Klik untuk ganti file</span>
                                    </div>

                                    <img id="image-preview"
                                        class="hidden w-full h-auto max-h-48 object-contain rounded-lg">
                                    <div id="pdf-preview" class="hidden text-center">
                                        <p class="text-red-600 font-bold">PDF Terpilih</p>
                                    </div>

                                    <input type="file" name="bukti" id="bukti-input"
                                        accept="image/*,application/pdf"
                                        class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                                <button type="button" id="reset-preview"
                                    class="hidden absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- SPECIAL GAS INFO --}}
                        @if ($pengeluaran->kategori === 'OPERASIONAL')
                            <div id="gas-info-card"
                                class="{{ $pengeluaran->sub_kategori !== 'GAS' ? 'hidden' : '' }} bg-blue-50 rounded-2xl p-6 border border-blue-100">
                                <div class="flex gap-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-xs text-blue-700">Sistem akan otomatis mencocokkan data pemakaian
                                        gas harian yang belum terbayar.</p>
                                </div>
                            </div>
                        @endif

                        {{-- ACTIONS --}}
                        <div class="flex flex-col gap-3 pt-4">
                            <button type="submit"
                                class="w-full bg-{{ $color === 'slate' ? 'slate-700' : ($color === 'blue' ? 'blue-600' : $color . '-700') }} hover:opacity-90 text-white font-bold py-4 rounded-xl shadow-lg flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Perubahan
                            </button>
                            <a href="{{ url()->previous() }}"
                                class="w-full bg-white border border-gray-200 text-gray-600 text-center py-4 rounded-xl font-semibold hover:bg-gray-50 transition-all">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Logika Preview File
        const input = document.getElementById('bukti-input');
        const imagePreview = document.getElementById('image-preview');
        const pdfPreview = document.getElementById('pdf-preview');
        const initialPreview = document.getElementById('initial-preview');
        const placeholder = document.getElementById('placeholder-content');
        const resetBtn = document.getElementById('reset-preview');

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                resetBtn.classList.remove('hidden');
                if (initialPreview) initialPreview.classList.add('hidden');
                placeholder.classList.add('hidden');

                if (file.type.startsWith('image/')) {
                    reader.onload = e => {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        pdfPreview.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.classList.add('hidden');
                    pdfPreview.classList.remove('hidden');
                }
            }
        });

        resetBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            input.value = '';
            imagePreview.classList.add('hidden');
            pdfPreview.classList.add('hidden');
            if (initialPreview) initialPreview.classList.remove('hidden');
            else placeholder.classList.remove('hidden');
            resetBtn.classList.add('hidden');
        });

        // Auto-Uppercase untuk input manual
        const subInput = document.getElementById('sub_kategori');
        if (subInput) {
            subInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        }
    </script>
</x-layout.beranda.app>
