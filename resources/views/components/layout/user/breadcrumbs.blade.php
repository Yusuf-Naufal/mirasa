<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center text-sm font-medium">

        @php
            // Menentukan route dashboard berdasarkan role
            $urlDashboard = '#';
            if (auth()->user()->hasRole('Super Admin')) {
                $urlDashboard = route('super-admin.dashboard');
            } elseif (auth()->user()->hasRole('Manager')) {
                $urlDashboard = route('manager.dashboard');
            } elseif (auth()->user()->hasRole('Admin Gudang')) {
                $urlDashboard = route('admin-gudang.dashboard');
            } else {
                $urlDashboard = route('admin-gudang.dashboard');
            }
        @endphp

        {{-- Tombol Home --}}
        <li class="inline-flex items-center">
            <a href="{{ $urlDashboard }}" class="text-gray-400 hover:text-[#FFC829] transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                    </path>
                </svg>
            </a>
        </li>

        @php $link = ""; @endphp

        @foreach (request()->segments() as $segment)
            @php
                $link .= '/' . $segment;

                // JIKA SEGMENT ADALAH ANGKA (ID), LANJUTKAN KE LOOP BERIKUTNYA (DIHILANGKAN)
                if (is_numeric($segment)) {
                    continue;
                }

                // Terjemahan manual
                $displayValue = $segment;
                if ($segment == 'edit') {
                    $displayValue = 'edit data';
                }
                if ($segment == 'create') {
                    $displayValue = 'tambah baru';
                }
            @endphp

            <li>
                <div class="flex items-center">
                    {{-- Separator --}}
                    <svg class="w-5 h-5 text-gray-300 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>

                    @if ($loop->last)
                        {{-- Segment Terakhir (Teks Tebal) --}}
                        <span class="text-gray-800 font-bold capitalize">
                            {{ str_replace('-', ' ', $displayValue) }}
                        </span>
                    @else
                        {{-- Segment Link --}}
                        <a href="{{ url($link) }}"
                            class="text-gray-500 hover:text-[#FFC829] capitalize transition-colors">
                            {{ str_replace('-', ' ', $displayValue) }}
                        </a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
