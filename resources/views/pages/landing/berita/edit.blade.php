<x-layout.user.app title="Edit Berita">
    <div class="py-2">
        <form action="{{ route('berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data"
            class="form-prevent-multiple-submits bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
            @csrf
            @method('PUT')

            <div class="p-6 md:p-8 space-y-8">
                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Edit Berita: {{ $berita->judul }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        {{-- Judul --}}
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Judul Berita <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul', $berita->judul) }}" required
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 border outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-500">
                        </div>

                        {{-- Gambar --}}
                        <div class="md:col-span-2 space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">Gambar Utama</label>
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <div id="preview-container"
                                    class="w-full md:w-64 h-36 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50">
                                    <img id="img-preview"
                                        src="{{ $berita->gambar_utama ? asset('storage/' . $berita->gambar_utama) : '' }}"
                                        class="{{ $berita->gambar_utama ? '' : 'hidden' }} object-cover w-full h-full">
                                    <div id="placeholder-icon"
                                        class="{{ $berita->gambar_utama ? 'hidden' : '' }} text-center">
                                        <svg class="mx-auto w-10 h-10 text-gray-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-full">
                                    <input type="hidden" id="cropped_image" name="cropped_image">
                                    <input type="file" id="temp_foto" accept="image/*" onchange="previewImage(this)"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                </div>
                            </div>
                        </div>

                        {{-- Kategori --}}
                        <div class="md:col-span-1 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Kategori <span
                                    class="text-red-500">*</span></label>
                            <select name="kategori" required
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 border outline-none">
                                @foreach (['Kegiatan', 'Pengumuman', 'Edukasi'] as $kat)
                                    <option value="{{ $kat }}"
                                        {{ old('kategori', $berita->kategori) == $kat ? 'selected' : '' }}>
                                        {{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Penulis --}}
                        <div class="md:col-span-1 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Penulis <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="penulis" value="{{ old('penulis', $berita->penulis) }}" required
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 border outline-none">
                        </div>

                        {{-- Ringkasan --}}
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Ringkasan <span
                                    class="text-red-500">*</span></label>
                            <textarea name="ringkasan" rows="2" required
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 border outline-none">{{ old('ringkasan', $berita->ringkasan) }}</textarea>
                        </div>

                        {{-- Konten --}}
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Isi Berita <span
                                    class="text-red-500">*</span></label>
                            <textarea name="konten" id="editor" rows="10"
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 border outline-none">{{ old('konten', $berita->konten) }}</textarea>
                        </div>

                        {{-- Status --}}
                        <div class="md:col-span-1 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Status Publikasi</label>
                            <div class="flex gap-4 p-1 bg-gray-100 rounded-xl w-fit">
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_publish" value="0" class="peer hidden"
                                        {{ $berita->status_publish ? '' : 'checked' }}>
                                    <span
                                        class="inline-block px-6 py-2 rounded-lg text-sm font-semibold peer-checked:bg-white peer-checked:text-blue-600 text-gray-500">Draft</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_publish" value="1" class="peer hidden"
                                        {{ $berita->status_publish ? 'checked' : '' }}>
                                    <span
                                        class="inline-block px-6 py-2 rounded-lg text-sm font-semibold peer-checked:bg-white peer-checked:text-green-600 text-gray-500">Publish</span>
                                </label>
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div class="md:col-span-1 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Tanggal Publish</label>
                            <input type="datetime-local" name="tanggal_publish"
                                value="{{ $berita->tanggal_publish ? $berita->tanggal_publish->format('Y-m-d\TH:i') : '' }}"
                                class="w-full rounded-xl border-gray-200 py-3.5 px-5 border outline-none">
                        </div>
                    </div> {{-- Akhir Grid --}}
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between gap-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 italic">Terakhir diupdate:
                    {{ $berita->updated_at->format('d M Y H:i') }}</p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('berita.index') }}"
                        class="px-6 py-2.5 text-sm font-semibold text-gray-600 rounded-xl border border-gray-200 hover:bg-gray-50">Batal</a>
                    <button type="submit"
                        class="px-8 py-2.5 text-sm font-bold text-white bg-yellow-600 hover:bg-yellow-700 rounded-xl shadow-sm transition-all active:scale-95">Update
                        Perubahan</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Modal Cropper (Tetap Sama) --}}
    <div id="cropper-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/75 p-4">
        <div class="bg-white rounded-2xl max-w-3xl w-full overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Ubah Ukuran Gambar</h3>
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
                    class="px-6 py-2 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700">Update
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
                        aspectRatio: 16 / 9,
                        viewMode: 2
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
                height: 720
            });
            preview.src = canvas.toDataURL('image/jpeg');
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
            inputHidden.value = canvas.toDataURL('image/jpeg');
            modal.classList.add('hidden');
        }
    </script>
</x-layout.user.app>
