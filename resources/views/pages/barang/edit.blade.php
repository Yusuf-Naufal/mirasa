<x-layout.user.app>
    <div class="py-2">

        <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
            @csrf
            @method('PUT') {{-- WAJIB untuk Update --}}

            <div class="p-6 md:p-8 space-y-8">
                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                        <div class="p-2 bg-amber-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="m14.06 9.02l.92.92L5.92 19H5v-.92l9.06-9.06M17.66 3c-.25 0-.51.1-.7.29l-1.83 1.83l3.75 3.75l1.83-1.83c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.2-.2-.45-.29-.71-.29m-3.6 3.19L3 17.25V21h3.75L17.81 9.94l-3.75-3.75Z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Edit Barang</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        <input type="hidden" id="cropped_image" name="cropped_image">

                        <div class="md:col-span-2 space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">Foto Barang</label>
                            <div class="flex items-center gap-6">
                                <div id="preview-container"
                                    class="w-32 h-32 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50">
                                    {{-- Tampilkan Foto Lama Jika Ada --}}
                                    @if ($barang->foto)
                                        <img id="img-preview" src="{{ asset('storage/' . $barang->foto) }}"
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
                                    <input type="file" id="temp_foto" accept="image/*" onchange="previewImage(this)"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                                    <p class="text-xs text-gray-400 mt-2 italic">*Kosongkan jika tidak ingin mengubah
                                        foto</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="nama_barang" class="block text-sm font-semibold text-gray-700">Nama Barang <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="nama_barang" name="nama_barang" required
                                value="{{ old('nama_barang', $barang->nama_barang) }}"
                                placeholder="Masukkan nama barang"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border">
                        </div>

                        <div class="space-y-1">
                            <label for="satuan" class="block text-sm font-semibold text-gray-700">Satuan Barang <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="satuan" name="satuan" required
                                value="{{ old('satuan', $barang->satuan) }}" placeholder="KG/ROLL/PAX"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border uppercase">
                        </div>

                        @if (auth()->user()->hasRole('Super Admin'))
                            <div class="md:col-span-2 space-y-1">
                                <label for="id_perusahaan" class="block text-sm font-semibold text-gray-700">Perusahaan
                                    <span class="text-red-500">*</span></label>
                                <select id="id_perusahaan" name="id_perusahaan" required
                                    class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border bg-white cursor-pointer">
                                    @foreach ($perusahaan as $p)
                                        <option value="{{ $p->id }}"
                                            {{ $barang->id_perusahaan == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="id_perusahaan" value="{{ auth()->user()->id_perusahaan }}">
                        @endif

                        <div class="space-y-1">
                            <label for="id_jenis" class="block text-sm font-semibold text-gray-700">Jenis Barang <span
                                    class="text-red-500">*</span></label>
                            <select id="id_jenis" name="id_jenis" required onchange="updateKodeJenis(this)"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border bg-white cursor-pointer">
                                @foreach ($jenis as $j)
                                    <option value="{{ $j->id }}" data-kode="{{ $j->kode }}"
                                        {{ $barang->id_jenis == $j->id ? 'selected' : '' }}>
                                        {{ $j->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="kode" class="block text-sm font-semibold text-gray-700">Kode Barang <span
                                    class="text-red-500">*</span></label>
                            <div class="relative flex">
                                {{-- Kita ambil kode murni setelah tanda - --}}
                                @php
                                    $kodeMurni =
                                        strpos($barang->kode, '-') !== false
                                            ? explode('-', $barang->kode)[1]
                                            : $barang->kode;
                                    $prefixAwal =
                                        strpos($barang->kode, '-') !== false ? explode('-', $barang->kode)[0] : '?';
                                @endphp
                                <span id="prefix-kode"
                                    class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 font-bold text-sm">
                                    {{ $prefixAwal }}
                                </span>
                                <input type="text" id="kode" name="kode" required placeholder="001"
                                    value="{{ old('kode', $kodeMurni) }}"
                                    class="w-full rounded-r-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border uppercase">
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
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-2.5 text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-xl transition-all active:scale-95 shadow-sm">
                        Update Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Modal Cropper (Tetap Sama) --}}
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
                    class="px-6 py-2 bg-green-500 text-white rounded-xl font-bold hover:bg-green-600">Simpan
                    Potongan</button>
            </div>
        </div>
    </div>

    {{-- Script (Gunakan Logic yang Sama) --}}
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
                height: 800
            });
            preview.src = canvas.toDataURL('image/jpeg');
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
            inputHidden.value = canvas.toDataURL('image/jpeg');
            modal.classList.add('hidden');
        }

        function updateKodeJenis(select) {
            const selectedOption = select.options[select.selectedIndex];
            const kode = selectedOption.getAttribute('data-kode');
            document.getElementById('prefix-kode').innerText = kode ? kode : '?';
        }
    </script>
</x-layout.user.app>
