<x-layout.user.app title="Tambah Produk">
    <div class="py-2">

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data"
            class="form-prevent-multiple-submits bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
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
                        <h2 class="text-lg font-semibold text-gray-800">Tambah Produk</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">


                        <div class="md:col-span-2 space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">Foto Produk <span
                                    class="text-red-500">*</span></label>
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

                        <div class="md:col-span-2 space-y-2">
                            <label for="nama_produk" class="block text-sm font-semibold text-gray-700">Nama Produk <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="nama_produk" name="nama_produk" required
                                placeholder="Misal: Keripik Pisang Original Mirasa"
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none placeholder-gray-400">
                        </div>

                        <div class="md:col-span-1 space-y-2">
                            <label for="rasa" class="block text-sm font-semibold text-gray-700">Rasa <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="rasa" name="rasa" required
                                placeholder="Misal: Original, Balado"
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none placeholder-gray-400">
                        </div>

                        <div class="md:col-span-1 space-y-2">
                            <label for="kategori" class="block text-sm font-semibold text-gray-700">Kategori <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="kategori" name="kategori" required
                                placeholder="Misal: Keripik Singkong"
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none placeholder-gray-400">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Deskripsi
                                Produk <span class="text-red-500">*</span></label>
                            <textarea name="deskripsi" rows="5" placeholder="Tuliskan detail produk, keunggulan, dan komposisi..."
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none placeholder-gray-400"></textarea>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <label
                                class="relative flex items-center justify-between p-4 rounded-2xl bg-white border border-gray-100 shadow-sm hover:border-blue-200 transition-all cursor-pointer group">
                                <div class="flex items-center">
                                    <div
                                        class="p-2.5 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-800">Status Publikasi</p>
                                        <p class="text-xs text-gray-400">Tampilkan produk di website</p>
                                    </div>
                                </div>

                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_aktif" id="is_aktif" checked class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </div>
                            </label>

                            <label
                                class="relative flex items-center justify-between p-4 rounded-2xl bg-white border border-gray-100 shadow-sm hover:border-yellow-200 transition-all cursor-pointer group">
                                <div class="flex items-center">
                                    <div
                                        class="p-2.5 rounded-xl bg-yellow-50 text-yellow-600 group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-800">Highlight</p>
                                        <p class="text-xs text-gray-400">Set sebagai produk unggulan</p>
                                    </div>
                                </div>

                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_unggulan" id="is_unggulan" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500">
                                    </div>
                                </div>
                            </label>

                        </div>

                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between gap-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 italic text-start">* Wajib diisi</p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('produk.index') }}"
                        class="flex-1 sm:flex-none text-center border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-600 rounded-xl hover:text-gray-800 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="btn-submit flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-2.5 text-sm font-bold text-white bg-green-500 hover:bg-green-600 rounded-xl transition-all active:scale-95 shadow-sm disabled:opacity-70 disabled:cursor-not-allowed">
                        <span class="btn-text">Simpan</span>
                        <svg class="btn-spinner hidden animate-spin ml-2 h-4 w-4 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
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

</x-layout.user.app>
