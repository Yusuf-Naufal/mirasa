<x-layout.beranda.app>

    <div class="md:px-10 py-6 flex flex-col">
        {{-- MENU FITUR --}}
        <div class="flex-1 pt-20 h-fit">
            <div class="max-w-7xl mb-6">
                <h1 class="text-xl font-bold text-gray-800">Menu Fitur</h1>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mx-auto max-w-7xl items-center">
                <a href="{{ route('super-admin.dashboard') }}"
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-blue-50">
                    <svg class="w-12 h-12 mb-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Dashboard</span>
                </a>

                <a href="{{ route('inventory.index') }}"
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-yellow-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-yellow-600" viewBox="0 0 16 16">
                        <path fill="currentColor"
                            d="M16 4L7.94 0L0 4v1h1v11h2V7h10v9h2V5h1zM4 6V5h2v1zm3 0V5h2v1zm3 0V5h2v1z" />
                        <path fill="currentColor" d="M6 9H5V8H4v3h3V8H6zm0 4H5v-1H4v3h3v-3H6zm4 0H9v-1H8v3h3v-3h-1z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700">Gudang</span>
                </a>

                <a href=""
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-green-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-green-600" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M3 6.25A3.25 3.25 0 0 1 6.25 3h11.5A3.25 3.25 0 0 1 21 6.25v4.762a3.28 3.28 0 0 0-2.61.95l-5.903 5.903a3.7 3.7 0 0 0-.931 1.57c-.345-.536-.87-.915-1.412-1.133c-.691-.278-1.385-.16-1.936.035a5.5 5.5 0 0 0-.729.326a.5.5 0 0 1-.089.07L5.693 19.77c-.467.275-.907.365-1.298.335a1.9 1.9 0 0 1-.9-.311c-.387-.255-.496-.683-.496-1.005V6.25m16.1 6.42l-5.903 5.902a2.7 2.7 0 0 0-.706 1.247l-.428 1.712c-.355.17-.71.202-1.133.105c-.126-.03-.18-.175-.127-.293c.43-.962-.19-1.776-1.03-2.113c-.955-.385-2.226.515-3.292 1.268c-.592.42-1.12.793-1.496.876c-.525.117-1.162-.123-1.631-.38c-.209-.113-.487.072-.388.288c.242.529.731 1.133 1.71 1.255c.98.121 1.766-.347 2.55-.815c.583-.348 1.165-.696 1.826-.799c.086-.013.144.088.105.166c-.242.484-.356 1.37.218 1.818c.848.662 3.237.292 3.828.088q.073-.007.148-.027l1.83-.457a2.7 2.7 0 0 0 1.248-.707l5.903-5.902a2.286 2.286 0 0 0-3.233-3.232" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Bahan Baku</span>
                </a>
            </div>
        </div>
    </div>

</x-layout.beranda.app>
