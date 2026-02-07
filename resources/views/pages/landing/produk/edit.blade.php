<x-layout.user.app title="Edit Produk">
    <div class="py-2">

        <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data"
            class="form-prevent-multiple-submits bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
            @csrf
            @method('PUT')

            <div class="p-6 md:p-8 space-y-8">

                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M5 3c-1.11 0-2 .89-2 2v14c0 1.11.89 2 2 2h14c1.11 0 2-.89 2-2v-7l-2 2v5H5V5h5l2-2H5m12.78 1a.69.69 0 0 0-.48.2l-1.22 1.21l2.5 2.5l1.22-1.22c.15-.14.22-.33.22-.51c0-.18-.07-.37-.22-.51l-1.56-1.56c-.14-.15-.33-.21-.51-.21m-2.04 2.04L8.5 13.25V15.5h2.25l7.24-7.24l-2.5-2.5Z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Edit Produk: {{ $produk->nama_produk }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        {{-- Input Foto --}}
                        <div class="md:col-span-2 space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">Foto Produk <span
                                    class="text-xs font-normal text-gray-400">(Kosongkan jika tidak ingin
                                    mengubah)</span></label>
                            <div class="flex items-center gap-6">
                                <div id="preview-container"
                                    class="w-32 h-32 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50">
                                    {{-- Jika ada foto lama, tampilkan --}}
                                    @if ($produk->foto)
                                        <img id="img-preview" src="{{ asset('storage/' . $produk->foto) }}"
                                            class="object-cover w-full h-full">
                                        <svg id="placeholder-icon" class="hidden w-12 h-12 text-gray-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @else
                                        <img id="img-preview" class="hidden object-cover w-full h-full">
                                        <svg id="placeholder-icon" class="w-12 h-12 text-gray-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="hidden" id="cropped_image" name="cropped_image">
                                    <input type="file" id="temp_foto" accept="image/*" onchange="previewImage(this)"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                                    <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG. Maksimal 2MB.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Nama Produk --}}
                        <div class="md:col-span-2 space-y-2">
                            <label for="nama_produk" class="block text-sm font-semibold text-gray-700">Nama Produk <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="nama_produk" name="nama_produk" required
                                value="{{ old('nama_produk', $produk->nama_produk) }}"
                                placeholder="Misal: Keripik Pisang Original Mirasa"
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none">
                        </div>

                        <div class="md:col-span-1 space-y-2">
                            <label for="rasa" class="block text-sm font-semibold text-gray-700">Rasa <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="rasa" name="rasa" required
                                value="{{ old('rasa', $produk->rasa) }}"
                                placeholder="Misal: Keripik Pisang Original Mirasa"
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none">
                        </div>

                        <div class="md:col-span-1 space-y-2">
                            <label for="kategori" class="block text-sm font-semibold text-gray-700">Kategori <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="kategori" name="kategori" required
                                value="{{ old('kategori', $produk->kategori) }}"
                                placeholder="Misal: Keripik Pisang Original Mirasa"
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none">
                        </div>

                        {{-- Deskripsi --}}
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Deskripsi Produk <span
                                    class="text-red-500">*</span></label>
                            <textarea name="deskripsi" rows="5" placeholder="Tuliskan detail produk..."
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 text-gray-900 shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all border outline-none">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Status Publikasi --}}
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
                                <div class="relative inline-flex items-center">
                                    {{-- INPUT HIDDEN: Menjamin nilai '0' terkirim jika tidak dicentang --}}
                                    <input type="checkbox" name="is_aktif" id="is_aktif" value="1"
                                        {{ $produk->is_aktif ? 'checked' : '' }} class="sr-only peer">

                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </div>
                            </label>

                            {{-- Highlight --}}
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
                                <div class="relative inline-flex items-center">
                                    {{-- INPUT HIDDEN: Menjamin nilai '0' terkirim jika tidak dicentang --}}
                                    <input type="checkbox" name="is_unggulan" id="is_unggulan" value="1"
                                        {{ $produk->is_unggulan ? 'checked' : '' }} class="sr-only peer">
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
                        class="btn-submit flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-2.5 text-sm font-bold text-white bg-yellow-600 hover:bg-yellow-700 rounded-xl transition-all active:scale-95 shadow-sm">
                        <span class="btn-text">Update Perubahan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Modal Cropper --}}
    <div id="cropper-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/75 p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Potong Gambar Baru</h3>
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
                    class="px-6 py-2 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700">Potong &
                    Simpan</button>
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
                        aspectRatio: 1 / 1,
                        viewMode: 2,
                        autoCropArea: 1,
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
                width: 800,
                height: 800,
            });

            preview.src = canvas.toDataURL('image/jpeg');
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');

            inputHidden.value = canvas.toDataURL('image/jpeg');
            modal.classList.add('hidden');
        }
    </script>
</x-layout.user.app>
