<nav class="fixed top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-gray-200">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                </button>

                <a href="#" class="flex ms-2 md:me-24 items-center group">
                    <div class="hidden md:block me-3 bg-none group-hover:scale-110 transition-transform duration-300">
                        <img src="{{ asset('assets/logo/logo_pt_mirasa_food-removebg-preview.png') }}"
                            alt="Logo PT Mirasa Food" class="w-10 h-10 object-contain">
                    </div>
                    <span
                        class="self-center text-base font-extrabold sm:text-lg whitespace-nowrap bg-clip-text text-transparent bg-gradient-to-r from-red-700 to-red-600">
                        MIRASA FOOD INDUSTRY
                    </span>
                </a>
            </div>

            <div class="flex items-center gap-2">
                <button class="p-2 text-gray-500 rounded-full hover:bg-gray-100 hover:text-blue-600 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center ms-3">
                    <button type="button"
                        class="flex items-center gap-3 p-1 text-sm bg-gray-50 rounded-full hover:bg-gray-100 transition-all border border-gray-100 shadow-sm"
                        id="user-menu-button">
                        <img class="w-8 h-8 rounded-full shadow-inner border border-white"
                            src="https://ui-avatars.com/api/?name=Super+Admin&background=2563EB&color=fff"
                            alt="user photo">
                        <div class="hidden md:block text-left pr-2">
                            <p class="text-sm font-semibold text-gray-800 leading-none">Admin Mirasa</p>
                            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">Super Admin
                            </p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
