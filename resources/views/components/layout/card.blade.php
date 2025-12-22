@props([
    'title' => null,
    'subtitle' => null
])

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm mb-4']) }} 
     style="border-radius: 1.25rem; overflow: hidden;">
    
    {{-- Card Header --}}
    @if ($title || isset($action))
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold" style="color: #1e293b; font-size: 1.1rem;">{{ $title }}</h5>
                @if($subtitle)
                    <small class="text-muted">{{ $subtitle }}</small>
                @endif
            </div>

            @if(isset($action))
                <div class="d-flex gap-2">
                    {{ $action }}
                </div>
            @endif
        </div>
    @endif

    {{-- Card Body --}}
    <div class="card-body p-4">
        {{ $slot }}
    </div>
</div>