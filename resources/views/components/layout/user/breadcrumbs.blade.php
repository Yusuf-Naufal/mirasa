<nav class="flex mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm font-medium">

        @php $link = ""; @endphp

        @foreach (request()->segments() as $segment)
            @php
                $link .= '/' . $segment;
                $isId = is_numeric($segment);
            @endphp

            @if ($loop->first)
                {{-- Segment Pertama: Menjadi Induk/Home (Contoh: Perusahaan) --}}
                <li class="inline-flex items-center">
                    <a href="{{ url($link) }}"
                        class="inline-flex items-center text-gray-700 hover:text-blue-600 transition-colors">
                        <span class="capitalize">{{ str_replace('-', ' ', $segment) }}</span>
                    </a>
                </li>
            @else
                {{-- Segment Kedua dan Seterusnya --}}
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>

                        @if ($loop->last)
                            <span class="ml-1 text-gray-800 font-bold capitalize md:ml-2">
                                {{ $isId ? 'Detail' : str_replace('-', ' ', $segment) }}
                            </span>
                        @else
                            <a href="{{ url($link) }}"
                                class="ml-1 text-gray-500 hover:text-blue-600 capitalize md:ml-2 transition-colors">
                                {{ $isId ? 'Item' : str_replace('-', ' ', $segment) }}
                            </a>
                        @endif
                    </div>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
