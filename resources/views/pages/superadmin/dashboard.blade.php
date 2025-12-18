<x-layout.user.app>

    <div class="space-y-2">
        
        <div class="relative overflow-hidden bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome back, Admin! 👋</h1>
                <p class="text-gray-500 max-w-md">Here's what's happening with Mirasa Food today. Check your daily
                    sales and update products below.</p>
            </div>
            <div class="absolute right-0 bottom-0 opacity-5 hidden lg:block">
                <svg class="w-64 h-64 -mb-10 -mr-10" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
            </div>
        </div>
    
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 rounded-xl text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Products</p>
                        <h3 class="text-2xl font-bold text-gray-800">1,240</h3>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layout.user.app>
