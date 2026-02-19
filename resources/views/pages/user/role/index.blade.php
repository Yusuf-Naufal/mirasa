<x-layout.user.app title="Daftar Role">

    <div class="space-y-2">
        <div class="flex flex-col gap-4 md:justify-between">

            <div class="flex w-full flex-col gap-3 md:w-auto md:flex-row md:items-center md:justify-between">
                <div class="flex gap-2">
                    @can('roles.create')
                        <a href="{{ route('roles.create') }}"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10s10-4.477 10-10S17.523 2 12 2m5 11h-4v4h-2v-4H7v-2h4V7h2v4h4z" />
                            </svg>
                            <span class="hidden md:block md:ml-2">Tambah Role</span>
                        </a>
                    @endcan

                </div>

                <form action="{{ route('roles.index') }}" method="GET" class="relative w-full md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 py-2 pl-10 pr-3 text-sm text-gray-700 placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#FFC829]"
                        placeholder="Cari...">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="absolute left-3 top-5 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </form>
            </div>
        </div>

        {{-- TABLE DAN CARD --}}
        <x-role.table :roles="$roles" />
        <x-role.card :roles="$roles" />

    </div>

</x-layout.user.app>
