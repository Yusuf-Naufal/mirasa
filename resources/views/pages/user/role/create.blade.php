<x-layout.user.app title="Tambah Role">
    <div class="py-2">
        <form action="{{ route('roles.store') }}" method="POST" class="form-prevent-multiple-submits space-y-6">
            @csrf

            {{-- Header Section --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div class="space-y-1">
                    <h1 class="text-2xl font-bold text-gray-900">Buat Role Baru</h1>
                    <p class="text-sm text-gray-500 font-medium">Atur nama jabatan dan izin akses sistem Mirasa.</p>
                </div>
            </div>

            {{-- Input Nama Role --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <div class="max-w-md">
                    <label for="name"
                        class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Jabatan /
                        Role</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        placeholder="Contoh: Admin Gudang"
                        class="w-full rounded-xl border-gray-200 bg-gray-50/50 py-3 px-4 text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-600 focus:bg-white outline-none transition-all font-semibold uppercase">
                </div>
            </div>

            {{-- Permissions Toolbar --}}
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative w-full md:w-80">
                    <input type="text" id="permissionSearch" placeholder="Cari modul atau akses..."
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-4 focus:ring-green-500/10 focus:border-green-600 outline-none transition-all text-sm font-medium">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider">
                    <button type="button" onclick="toggleAll(true)"
                        class="text-green-600 hover:bg-green-50 px-3 py-2 rounded-lg transition-all">Pilih
                        Semua</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" onclick="toggleAll(false)"
                        class="text-gray-400 hover:bg-gray-50 px-3 py-2 rounded-lg transition-all">Reset</button>
                </div>
            </div>

            {{-- Permissions Grid --}}
            <div class="grid grid-cols-1 gap-6">
                @foreach ($permissions->groupBy(fn($item) => explode('.', $item->name)[0]) as $group => $items)
                    <div class="permission-group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-all overflow-hidden"
                        data-group-name="{{ strtolower($group) }}">

                        {{-- Header Group --}}
                        <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <h3 class="text-sm font-black text-gray-700 uppercase tracking-widest">
                                    {{ $group }}</h3>
                            </div>
                            <button type="button" onclick="toggleGroup('{{ $group }}', true)"
                                class="text-[10px] font-extrabold text-green-600 hover:text-green-700 bg-green-50 px-3 py-1.5 rounded-lg transition-colors tracking-tighter">
                                PILIH GRUP
                            </button>
                        </div>

                        <div class="p-6 space-y-6">
                            {{-- View Section --}}
                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Tampilan</span>
                                    <div class="h-px flex-1 bg-gray-100"></div>
                                </div>
                                {{-- Grid yang melebar otomatis (Auto-fit) --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                    @foreach ($items as $permission)
                                        @if (!preg_match('/(create|edit|delete|update|store|destroy|import|activate|print)/i', $permission->name))
                                            <label
                                                class="permission-item group flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-green-200 hover:bg-green-50/30 transition-all cursor-pointer"
                                                data-permission-name="{{ strtolower($permission->name) }}">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $permission->name }}" data-group="{{ $group }}"
                                                    class="permission-checkbox view-checkbox w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-0 cursor-pointer transition-transform group-hover:scale-110">
                                                <span
                                                    class="text-xs font-bold text-gray-600 capitalize group-hover:text-green-700 transition-colors">
                                                    {{ str_replace(['-', '_'], ' ', last(explode('.', $permission->name))) }}
                                                </span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            {{-- CRUD Section --}}
                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Tindakan</span>
                                    <div class="h-px flex-1 bg-gray-100"></div>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                    @foreach ($items as $permission)
                                        @if (preg_match('/(create|edit|delete|update|store|destroy|import|activate|print)/i', $permission->name))
                                            <label
                                                class="permission-item group flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/30 transition-all cursor-pointer"
                                                data-permission-name="{{ strtolower($permission->name) }}">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $permission->name }}" data-group="{{ $group }}"
                                                    class="permission-checkbox action-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-0 cursor-pointer transition-transform group-hover:scale-110">
                                                <span
                                                    class="text-xs font-bold text-gray-600 capitalize group-hover:text-blue-700 transition-colors">
                                                    {{ str_replace(['-', '_'], ' ', last(explode('.', $permission->name))) }}
                                                </span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="sticky bottom-6 z-50 mt-12">
                <div
                    class="bg-white/80 backdrop-blur-md border border-gray-200 px-6 py-4 rounded-2xl shadow-2xl flex items-center justify-between">
                    <div class="hidden md:block">
                        <p class="text-xs text-gray-400 font-medium">Pastikan semua hak akses sudah sesuai sebelum
                            menyimpan.</p>
                    </div>

                    <div class="flex gap-3 w-full md:w-auto">
                        <a href="{{ route('roles.index') }}"
                            class="flex-1 md:flex-none text-center px-6 py-3 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition-all">
                            Batal
                        </a>
                        <button type="submit"
                            class="btn-submit flex-1 md:flex-none px-10 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-extrabold rounded-xl shadow-lg shadow-green-200 transition-all active:scale-95 uppercase tracking-wider">
                            <span class="btn-text">Simpan</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let isBulkOperating = false;

        document.addEventListener('change', function(e) {
            if (isBulkOperating) return;

            const target = e.target;
            if (!target.classList.contains('permission-checkbox')) return;

            const group = target.getAttribute('data-group');
            const permissionName = target.value;
            const subModulKeywords = ['maintenance', 'kesejahtraan', 'operasional', 'office', 'limbah',
                'administrasi', 'produksi', 'bahan-baku', 'pemakaian', 'hpp', 'transaksi', 'gudang',
                'penjualan', 'bahan-penolong', 'inventory'
            ];

            // Logic CHECK
            if (target.classList.contains('action-checkbox') && target.checked) {
                const matchedKeyword = subModulKeywords.find(key => permissionName.includes(key));
                const viewCheckboxes = document.querySelectorAll(`.view-checkbox[data-group="${group}"]`);

                if (matchedKeyword) {
                    const viewToTick = Array.from(viewCheckboxes).find(view => view.value.includes(matchedKeyword));
                    if (viewToTick) viewToTick.checked = true;
                } else {
                    viewCheckboxes.forEach(view => view.checked = true);
                }
            }

            // Logic UNCHECK
            if (target.classList.contains('view-checkbox') && !target.checked) {
                const matchedKeyword = subModulKeywords.find(key => permissionName.includes(key));
                const actionCheckboxes = document.querySelectorAll(`.action-checkbox[data-group="${group}"]`);
                actionCheckboxes.forEach(action => {
                    if (matchedKeyword && action.value.includes(matchedKeyword)) action.checked = false;
                    else if (!matchedKeyword) action.checked = false;
                });
            }
        });

        function toggleGroup(group, checked) {
            isBulkOperating = true;
            document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`).forEach(el => el.checked = checked);
            isBulkOperating = false;
        }

        function toggleAll(checked) {
            isBulkOperating = true;
            document.querySelectorAll('.permission-checkbox').forEach(el => el.checked = checked);
            isBulkOperating = false;
        }

        document.getElementById('permissionSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().replace(/-/g, ' ');
            document.querySelectorAll('.permission-group').forEach(group => {
                const groupName = group.getAttribute('data-group-name').replace(/-/g, ' ');
                let hasVisibleItem = false;

                group.querySelectorAll('.permission-item').forEach(item => {
                    const name = item.getAttribute('data-permission-name');
                    if (name.includes(searchTerm) || groupName.includes(searchTerm)) {
                        item.style.display = 'flex';
                        hasVisibleItem = true;
                    } else {
                        item.style.display = 'none';
                    }
                });
                group.style.display = hasVisibleItem ? 'block' : 'none';
            });
        });
    </script>
</x-layout.user.app>
