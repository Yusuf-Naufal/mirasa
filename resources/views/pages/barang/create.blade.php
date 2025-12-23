<x-layout.user.app>
    <div class="py-2">

        <form action="{{ route('barang.index.store') }}" method="POST" enctype="multipart/form-data"
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

                        <div class="md:col-span-2 space-y-1">
                            <label for="nama_barang" class="block text-sm font-semibold text-gray-700">Nama Barang <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="nama_barang" name="nama_barang" required
                                placeholder="Masukkan nama barang"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border">
                        </div>

                        <div class="md:col-span-2 space-y-1">
                            <label for="id_perusahaan" class="block text-sm font-semibold text-gray-700">Perusahaan
                                <span class="text-red-500">*</span></label>
                            <select id="id_perusahaan" name="id_perusahaan" required
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border bg-white cursor-pointer">
                                <option value="" disabled selected>-- Pilih Perusahaan --</option>
                                @foreach ($perusahaan as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="id_jenis" class="block text-sm font-semibold text-gray-700">Jenis Barang <span
                                    class="text-red-500">*</span></label>
                            <select id="id_jenis" name="id_jenis" required onchange="updateKodeJenis(this)"
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
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between gap-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 italic text-start">* Wajib diisi</p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('barang.index.index') }}"
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

        // Update fungsi Kode tetap seperti sebelumnya
        function updateKodeJenis(select) {
            const selectedOption = select.options[select.selectedIndex];
            const kode = selectedOption.getAttribute('data-kode');
            document.getElementById('prefix-kode').innerText = kode ? kode : '?';
        }
    </script>
</x-layout.user.app>
