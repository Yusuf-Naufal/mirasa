<x-layout.user.app title="Tambah Barang">
    <div class="py-2">

        <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
            @csrf

            <div class="p-6 md:p-8 space-y-8">

                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                        <div class="p-2 bg-green-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="m17.578 4.432l-2-1.05C13.822 2.461 12.944 2 12 2s-1.822.46-3.578 1.382l-.321.169l8.923 5.099l4.016-2.01c-.646-.732-1.688-1.279-3.462-2.21m4.17 3.534l-3.998 2V13a.75.75 0 0 1-1.5 0v-2.286l-3.5 1.75v9.44c.718-.179 1.535-.607 2.828-1.286l2-1.05c2.151-1.129 3.227-1.693 3.825-2.708c.597-1.014.597-2.277.597-4.8v-.117c0-1.893 0-3.076-.252-3.978M11.25 21.904v-9.44l-8.998-4.5C2 8.866 2 10.05 2 11.941v.117c0 2.525 0 3.788.597 4.802c.598 1.015 1.674 1.58 3.825 2.709l2 1.049c1.293.679 2.11 1.107 2.828 1.286M2.96 6.641l9.04 4.52l3.411-1.705l-8.886-5.078l-.103.054c-1.773.93-2.816 1.477-3.462 2.21" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Tambah Barang</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">


                        <div class="md:col-span-2 space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">Foto Barang</label>
                            <div class="flex items-center gap-6">
                                <div id="preview-container"
                                    class="w-32 h-32 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50">
                                    <img id="img-preview" class="hidden object-cover w-full h-full">
                                    <svg id="placeholder-icon" class="w-12 h-12 text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <input type="hidden" id="cropped_image" name="cropped_image">

                                    <input type="file" id="temp_foto" accept="image/*" onchange="previewImage(this)"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                                    <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG. Maksimal 2MB.</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="nama_barang" class="block text-sm font-semibold text-gray-700">Nama Barang <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="nama_barang" name="nama_barang" required
                                placeholder="Masukkan nama barang"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border">
                        </div>

                        <div class="space-y-1">
                            <label for="satuan" class="block text-sm font-semibold text-gray-700">Satuan Barang <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="satuan" name="satuan" required placeholder="KG/ROLL/PAX"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border uppercase">
                        </div>

                        @if (auth()->user()->hasRole('Super Admin'))
                            <div class="md:col-span-2 space-y-1">
                                <label for="id_perusahaan" class="block text-sm font-semibold text-gray-700">Perusahaan
                                    <span class="text-red-500">*</span></label>
                                <select id="id_perusahaan" name="id_perusahaan" required
                                    class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border bg-white cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Perusahaan --</option>
                                    @foreach ($perusahaan as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}
                                            ({{ $p->kota }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="id_perusahaan" value="{{ auth()->user()->id_perusahaan }}">
                        @endif

                        <div class="space-y-1">
                            <label for="id_jenis" class="block text-sm font-semibold text-gray-700">Jenis Barang <span
                                    class="text-red-500">*</span></label>
                            <select id="id_jenis" name="id_jenis" required onchange="handleJenisChange(this)"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border bg-white cursor-pointer">
                                <option value="" disabled selected>-- Pilih Jenis --</option>
                                @foreach ($jenis as $j)
                                    <option value="{{ $j->id }}" data-kode="{{ $j->kode }}">
                                        {{ $j->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="kode" class="block text-sm font-semibold text-gray-700">Kode Barang <span
                                    class="text-red-500">*</span></label>
                            <div class="relative flex">
                                <span id="prefix-kode"
                                    class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 font-bold text-sm">
                                    ?
                                </span>
                                <input type="text" id="kode" name="kode" required placeholder="001"
                                    class="w-full rounded-r-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border uppercase">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 italic">*Kode final akan tersimpan otomatis dengan
                                format: KODE_JENIS-KODE_BARANG</p>
                        </div>

                        <div id="section-sub-bb"
                            class="hidden space-y-4 p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl border border-blue-100 md:col-span-2 shadow-inner">

                            <div class="flex items-center gap-2">
                                <div class="p-1.5 bg-blue-600 rounded-lg shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 7h10M7 12h10m-7 5h7" />
                                    </svg>
                                </div>
                                <p class="text-[11px] font-black text-blue-800 uppercase tracking-widest">
                                    Klasifikasi Bahan Baku <span class="text-red-500">*</span>
                                </p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Opsi Bahan Baku Utama --}}
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="jenis" value="Utama" class="peer hidden">
                                    <div
                                        class="flex items-center gap-4 p-4 bg-white border-2 border-transparent rounded-2xl shadow-sm transition-all duration-300 
                                            peer-checked:border-blue-600 peer-checked:bg-blue-50/50 group-hover:bg-blue-50/30">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-xl bg-blue-100 text-blue-600 transition-colors peer-checked:bg-blue-600 peer-checked:text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-800 tracking-tight">Bahan Baku Utama
                                            </h4>
                                            <p class="text-[10px] text-gray-500 font-medium uppercase italic">Bahan
                                                Inti Produksi (Umbi)</p>
                                        </div>
                                        <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                            <div
                                                class="w-5 h-5 bg-blue-600 rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                {{-- Opsi Bahan Baku Pendukung --}}
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="jenis" value="Pendukung" class="peer hidden">
                                    <div
                                        class="flex items-center gap-4 p-4 bg-white border-2 border-transparent rounded-2xl shadow-sm transition-all duration-300 
                                            peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 group-hover:bg-indigo-50/30">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 transition-colors peer-checked:bg-indigo-600 peer-checked:text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-800 tracking-tight">Bahan Pendukung
                                            </h4>
                                            <p class="text-[10px] text-gray-500 font-medium uppercase italic">Minyak, Dll</p>
                                        </div>
                                        <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                            <div
                                                class="w-5 h-5 bg-indigo-600 rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center gap-2 px-1">
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                </span>
                                <p class="text-[9px] text-blue-500 font-bold italic tracking-wide">Pilih kategori yang
                                    sesuai untuk memisahkan laporan</p>
                            </div>
                        </div>

                        {{-- KONVERSI --}}
                        <div id="section-konversi"
                            class="space-y-4 mt-2 p-5 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 md:col-span-2 transition-all">

                            {{-- Header & Info Badge --}}
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#FFC829]/10 text-[#FFC829]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    </span>
                                    <p class="text-sm font-bold text-gray-700">Pengaturan Konversi Satuan <span
                                            class="text-xs font-normal text-gray-400">(Opsional)</span></p>
                                </div>
                                <span
                                    class="text-[10px] bg-blue-50 text-blue-600 px-3 py-1 rounded-full font-semibold border border-blue-100">
                                    💡 INFO: Kosongkan jika tidak ada konversi
                                </span>
                            </div>

                            {{-- Alert Tip Khusus KG --}}
                            <div id="tip-kg"
                                class="flex items-start gap-3 p-3 bg-amber-50 border border-amber-100 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mt-0.5"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-xs text-amber-700 leading-relaxed">
                                    <span class="font-bold">Tips Satuan KG:</span> Jika satuan barang sudah <span
                                        class="font-bold underline">KG</span>, silakan isi angka <span
                                        class="font-bold text-amber-900">1</span> pada inputan berat di bawah ini.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Konversi Berat --}}
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Berat per <span class="label-satuan text-green-600">Satuan</span>
                                        <span id="req-icon" class="text-red-500 hidden">*</span>
                                    </label>
                                    <div class="relative group">
                                        <input type="number" step="0.01" id="nilai_konversi"
                                            name="nilai_konversi" placeholder="Contoh: 6"
                                            class="w-full rounded-xl border-gray-300 py-3 px-4 focus:ring-2 focus:ring-[#FFC829]/20 focus:border-[#FFC829] border shadow-sm transition-all group-hover:border-gray-400">
                                        <div
                                            class="absolute right-4 top-3 flex items-center gap-1 border-l pl-3 border-gray-200">
                                            <span class="text-gray-500 font-bold text-sm">Kg</span>
                                        </div>
                                    </div>
                                    <p id="helper-text-konversi" class="text-[10px] text-gray-400 italic">Masukkan
                                        total berat dalam kilogram.</p>
                                </div>

                                {{-- Konversi Isi --}}
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Isi per <span class="label-satuan text-green-600">Satuan</span>
                                    </label>
                                    <div class="relative group">
                                        <input type="number" name="isi_bungkus" placeholder="Contoh: 40"
                                            class="w-full rounded-xl border-gray-300 py-3 px-4 focus:ring-2 focus:ring-[#FFC829]/20 focus:border-[#FFC829] border shadow-sm transition-all group-hover:border-gray-400">
                                        <div
                                            class="absolute right-4 top-3 flex items-center gap-1 border-l pl-3 border-gray-200">
                                            <span class="text-gray-500 font-bold text-sm">Bks/Pcs</span>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-400 italic">Masukkan jumlah isi dalam satu kemasan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between gap-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 italic text-start">* Wajib diisi</p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('barang.index') }}"
                        class="flex-1 sm:flex-none text-center border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-600 rounded-xl hover:text-gray-800 transition">
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

    {{-- Modal Cropper --}}
    <div id="cropper-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/75 p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Potong Gambar</h3>
                <button type="button" onclick="closeCropper()"
                    class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <div class="p-4">
                <div class="max-h-[60vh] overflow-hidden bg-gray-100 rounded-lg">
                    <img id="cropper-image" src="" class="max-w-full block">
                </div>
            </div>
            <div class="p-4 border-t flex justify-end gap-3">
                <button type="button" onclick="closeCropper()"
                    class="px-4 py-2 text-gray-600 font-semibold">Batal</button>
                <button type="button" onclick="cropAndSave()"
                    class="px-6 py-2 bg-green-500 text-white rounded-xl font-bold hover:bg-green-600">Potong &
                    Simpan</button>
            </div>
        </div>
    </div>

    {{-- HANDLE IMAGE DAN KODE --}}
    <script>
        let cropper;
        const modal = document.getElementById('cropper-modal');
        const image = document.getElementById('cropper-image');
        const preview = document.getElementById('img-preview');
        const placeholder = document.getElementById('placeholder-icon');
        const inputHidden = document.getElementById('cropped_image');

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    image.src = e.target.result;
                    modal.classList.remove('hidden'); // Tampilkan Modal

                    // Inisialisasi Cropper
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(image, {
                        aspectRatio: 1 / 1, // Atur rasio 1:1 untuk kotak, atau 4/3
                        viewMode: 2,
                        autoCropArea: 1,
                    });
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeCropper() {
            modal.classList.add('hidden');
            document.getElementById('foto').value = ""; // Reset input file jika batal
        }

        function cropAndSave() {
            const canvas = cropper.getCroppedCanvas({
                width: 800, // Resolusi hasil crop
                height: 800,
            });

            // Tampilkan preview di halaman utama
            preview.src = canvas.toDataURL('image/jpeg');
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');

            // Masukkan data Base64 ke input hidden agar terkirim ke server
            inputHidden.value = canvas.toDataURL('image/jpeg');

            modal.classList.add('hidden');
        }
    </script>

    {{-- HANDLE CONVERSI --}}
    <script>
        function handleJenisChange(selectElement) {
            // 1. Jalankan fungsi prefix kode bawaan
            updateKodeJenis(selectElement);

            // 2. Identifikasi Pilihan
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const kodeJenis = selectedOption.getAttribute('data-kode');
            const inputNilai = document.getElementById('nilai_konversi');
            const reqIcon = document.getElementById('req-icon');
            const helperText = document.getElementById('helper-text-konversi');

            // Identifikasi section sub-bb
            const sectionSubBB = document.getElementById('section-sub-bb');
            const radioBB = document.getElementsByName('tipe_bahan_baku');

            // Logic Tampilkan Radio Button jika kode = BB
            if (kodeJenis === 'BB') {
                sectionSubBB.classList.remove('hidden');
                // Tambahkan atribut required pada radio button agar wajib diisi
                radioBB.forEach(radio => radio.setAttribute('required', 'required'));
            } else {
                sectionSubBB.classList.add('hidden');
                // Hapus atribut required jika bukan BB
                radioBB.forEach(radio => {
                    radio.removeAttribute('required');
                    radio.checked = false; // Reset pilihan
                });
            }

            // Daftar kode yang mewajibkan konversi (FG, WIP, EC)
            const wajibIsi = ['FG', 'WIP', 'EC'];

            if (wajibIsi.includes(kodeJenis)) {
                inputNilai.setAttribute('required', 'required');
                reqIcon.classList.remove('hidden');
                helperText.classList.add('text-red-500', 'font-medium');
                helperText.innerText = "* Wajib diisi untuk jenis " + kodeJenis;
            } else {
                inputNilai.removeAttribute('required');
                reqIcon.classList.add('hidden');
                helperText.classList.remove('text-red-500', 'font-medium');
                helperText.innerText = "Masukkan total berat dalam kilogram (Opsional).";
            }

            updateLabelSatuan();
        }

        function updateKodeJenis(select) {
            const prefix = document.getElementById('prefix-kode');
            const selectedOption = select.options[select.selectedIndex];
            const kode = selectedOption.getAttribute('data-kode');
            prefix.innerText = kode ? kode : '?';
        }

        function updateLabelSatuan() {
            const inputSatuan = document.getElementById('satuan').value || 'Satuan';
            document.querySelectorAll('.label-satuan').forEach(el => {
                el.innerText = inputSatuan;
            });
        }

        // Listener saat input satuan diketik
        document.getElementById('satuan').addEventListener('input', function() {
            updateLabelSatuan();
        });

        // Jalankan sekali saat halaman dimuat untuk inisialisasi label
        document.addEventListener('DOMContentLoaded', function() {
            updateLabelSatuan();
        });
    </script>
</x-layout.user.app>
