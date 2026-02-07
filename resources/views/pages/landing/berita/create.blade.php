<x-layout.user.app title="Buat Berita Baru">
    <div class="py-2">
        <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data"
            class="form-prevent-multiple-submits bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
            @csrf

            <div class="p-6 md:p-8 space-y-8">
                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10l4 4v10a2 2 0 0 1-2 2z"></path>
                                <polyline points="7 8 12 8"></polyline>
                                <polyline points="7 12 12 12"></polyline>
                                <polyline points="7 16 16 16"></polyline>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Tulis Berita Baru</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        {{-- Judul Berita --}}
                        <div class="md:col-span-2 space-y-2">
                            <label for="judul" class="block text-sm font-semibold text-gray-700">Judul Berita <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="judul" name="judul" required
                                placeholder="Masukkan judul berita yang menarik..."
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none">
                        </div>

                        {{-- Gambar Utama (Thumbnail) --}}
                        <div class="md:col-span-2 space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">Gambar Utama <span
                                    class="text-red-500">*</span></label>
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <div id="preview-container"
                                    class="w-full md:w-64 h-36 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50">
                                    <img id="img-preview" class="hidden object-cover w-full h-full">
                                    <div id="placeholder-icon" class="text-center">
                                        <svg class="mx-auto w-10 h-10 text-gray-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-xs text-gray-400 mt-1">Preview Gambar</p>
                                    </div>
                                </div>
                                <div class="flex-1 w-full">
                                    <input type="hidden" id="cropped_image" name="cropped_image">
                                    <input type="file" id="temp_foto" accept="image/*" onchange="previewImage(this)"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                    <p class="text-xs text-gray-400 mt-2">Rekomendasi rasio 16:9. Format: JPG, PNG. Maks
                                        2MB.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Kategori & Penulis --}}
                        <div class="md:col-span-1 space-y-2">
                            <label for="kategori" class="block text-sm font-semibold text-gray-700">Kategori <span
                                    class="text-red-500">*</span></label>
                            <select name="kategori" id="kategori" required
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none">
                                <option value="">Pilih Kategori</option>
                                <option value="Kegiatan">Kegiatan</option>
                                <option value="Pengumuman">Pengumuman</option>
                                <option value="Edukasi">Edukasi</option>
                            </select>
                        </div>

                        <div class="md:col-span-1 space-y-2">
                            <label for="penulis" class="block text-sm font-semibold text-gray-700">Penulis <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="penulis" name="penulis" value="{{ auth()->user()->name ?? '' }}"
                                required
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none">
                        </div>

                        {{-- Ringkasan --}}
                        <div class="md:col-span-2 space-y-2">
                            <label for="ringkasan" class="block text-sm font-semibold text-gray-700">Ringkasan <span
                                    class="text-red-500">*</span></label>
                            <textarea name="ringkasan" rows="2" placeholder="Tuliskan ringkasan singkat berita..." required
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none"></textarea>
                        </div>

                        {{-- Konten Berita --}}
                        <div class="md:col-span-2 space-y-2">
                            <label for="konten" class="block text-sm font-semibold text-gray-700">Isi Berita <span
                                    class="text-red-500">*</span></label>
                            <textarea name="konten" id="editor" rows="10" placeholder="Tulis isi berita lengkap di sini..."
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none"></textarea>
                        </div>

                        {{-- Status Publish --}}
                        <div class="md:col-span-1 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Status Publikasi</label>
                            <div class="flex gap-4 p-1 bg-gray-100 rounded-xl w-fit">
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_publish" value="0" class="peer hidden">
                                    <span
                                        class="inline-block px-6 py-2 rounded-lg text-sm font-semibold peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition-all text-gray-500">Draft</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_publish" value="1" class="peer hidden"
                                        checked>
                                    <span
                                        class="inline-block px-6 py-2 rounded-lg text-sm font-semibold peer-checked:bg-white peer-checked:text-green-600 peer-checked:shadow-sm transition-all text-gray-500">Publish</span>
                                </label>
                            </div>
                        </div>

                        {{-- Tanggal Publish (Optional jika ingin dijadwalkan) --}}
                        <div class="md:col-span-1 space-y-2">
                            <label for="tanggal_publish" class="block text-sm font-semibold text-gray-700">Tanggal
                                Publish</label>
                            <input type="datetime-local" id="tanggal_publish" name="tanggal_publish"
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none">
                        </div>

                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between gap-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 italic">* `Slug` dan `Jumlah View` akan dihandle otomatis oleh sistem.
                </p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('berita.index') }}"
                        class="flex-1 sm:flex-none text-center border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-600 rounded-xl hover:text-gray-800 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="btn-submit flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-2.5 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-xl transition-all active:scale-95 shadow-sm">
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
        <div class="bg-white rounded-2xl max-w-3xl w-full overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Sesuaikan Ukuran Gambar Berita</h3>
                <button type="button" onclick="closeCropper()"
                    class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-4 bg-gray-50">
                <div class="max-h-[60vh] overflow-hidden rounded-lg">
                    <img id="cropper-image" src="" class="max-w-full block">
                </div>
            </div>
            <div class="p-4 border-t flex justify-end gap-3">
                <button type="button" onclick="closeCropper()"
                    class="px-4 py-2 text-gray-600 font-semibold">Batal</button>
                <button type="button" onclick="cropAndSave()"
                    class="px-6 py-2 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700">Gunakan
                    Gambar</button>
            </div>
        </div>
    </div>

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
                    modal.classList.remove('hidden');
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(image, {
                        aspectRatio: 16 / 9, // Rasio standar berita
                        viewMode: 2,
                    });
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeCropper() {
            modal.classList.add('hidden');
            document.getElementById('temp_foto').value = "";
        }

        function cropAndSave() {
            const canvas = cropper.getCroppedCanvas({
                width: 1280,
                height: 720,
            });
            preview.src = canvas.toDataURL('image/jpeg');
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            inputHidden.value = canvas.toDataURL('image/jpeg');
            modal.classList.add('hidden');
        }
    </script>
</x-layout.user.app>
