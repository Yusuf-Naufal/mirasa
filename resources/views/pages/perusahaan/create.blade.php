<x-layout.user.app>
    <div class="py-2">

        <form action="{{ route('perusahaan.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
            @csrf

            <div class="p-6 md:p-8 space-y-8">

                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                        <div class="p-2 bg-green-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Detail Perusahaan</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        {{-- Input Logo --}}
                        <div
                            class="md:col-span-2 flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-colors group relative">
                            <label for="logo_input" class="cursor-pointer flex flex-col items-center">
                                <div id="preview-container"
                                    class="w-32 h-32 rounded-xl overflow-hidden bg-white shadow-inner mb-3 flex items-center justify-center border border-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-12 w-12 text-gray-400 group-hover:text-green-500 transition-colors"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600">Klik untuk unggah Logo Perusahaan</span>
                                <span class="text-xs text-gray-400 mt-1">PNG, JPG (Rasio 1:1 disarankan)</span>
                            </label>
                            <input type="file" id="logo_input" accept="image/*" class="hidden">
                            <input type="hidden" name="logo_cropped" id="logo_cropped">
                        </div>

                        <div class="space-y-1">
                            <label for="nama_perusahaan" class="block text-sm font-semibold text-gray-700">
                                Nama Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_perusahaan" name="nama_perusahaan" required
                                placeholder="Masukkan nama resmi perusahaan"
                                class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-gray-900 shadow-sm focus:outline-none focus:border-[#FFC829] transition-colors border">
                        </div>

                        <div class="space-y-1">
                            <label for="kota" class="block text-sm font-semibold text-gray-700">
                                Kota Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="kota" name="kota" required
                                placeholder="Masukkan kota perusahaan"
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

            <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between gap-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 italic">* Wajib diisi</p>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('perusahaan.index') }}"
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

    {{-- MODAL CROPPER --}}
    <div id="cropperModal" class="fixed inset-0 z-[999] hidden bg-black/80 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full overflow-hidden shadow-2xl">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Sesuaikan Logo</h3>
                <button type="button" onclick="closeCropper()"
                    class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-4 bg-gray-100 flex justify-center">
                <div class="max-h-[60vh] overflow-hidden">
                    <img id="imageToCrop" src="" class="max-w-full block">
                </div>
            </div>
            <div class="p-4 border-t flex justify-end gap-3">
                <button type="button" onclick="closeCropper()"
                    class="px-5 py-2 text-sm font-semibold text-gray-600">Batal</button>
                <button type="button" onclick="cropAndSave()"
                    class="px-6 py-2 bg-green-500 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-green-600">Potong
                    & Simpan</button>
            </div>
        </div>
    </div>

    <script>
        let cropper;
        const logoInput = document.getElementById('logo_input');
        const imageToCrop = document.getElementById('imageToCrop');
        const modal = document.getElementById('cropperModal');
        const previewContainer = document.getElementById('preview-container');
        const hiddenInput = document.getElementById('logo_cropped');

        logoInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imageToCrop.src = event.target.result;
                    modal.classList.remove('hidden');
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1, // Memaksa rasio kotak (persegi)
                        viewMode: 2,
                        autoCropArea: 1,
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        });

        function closeCropper() {
            modal.classList.add('hidden');
            logoInput.value = ""; // Reset input file
        }

        function cropAndSave() {
            const canvas = cropper.getCroppedCanvas({
                width: 500, // Ukuran standar simpan
                height: 500,
            });

            const croppedImage = canvas.toDataURL('image/png');
            hiddenInput.value = croppedImage;

            // Tampilkan preview
            previewContainer.innerHTML = `<img src="${croppedImage}" class="w-full h-full object-cover">`;

            closeCropper();
        }
    </script>
</x-layout.user.app>
