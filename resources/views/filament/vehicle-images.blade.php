@php
    $mainImageUrl = $vehicle->getMainImageUrl();
    $galleryUrls = $vehicle->getGalleryUrls();
    $galleryCount = is_array($galleryUrls) ? count($galleryUrls) : $galleryUrls->count();
@endphp

<div class="space-y-4">
    @if($mainImageUrl || $galleryCount > 0)
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-3">Vehicle Images ({{ $galleryCount + ($mainImageUrl ? 1 : 0) }})</h4>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @if($mainImageUrl)
                    <div class="relative group">
                        <img src="{{ $mainImageUrl }}" 
                             alt="{{ $vehicle->name }} - Main Image" 
                             class="w-full h-24 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"
                             loading="lazy">
                        <div class="absolute top-1 right-1">
                            <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs font-semibold shadow-lg">
                                Main
                            </span>
                        </div>
                    </div>
                @endif
                
                @foreach($galleryUrls as $index => $imageUrl)
                    <div class="relative group">
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $vehicle->name }} - Gallery {{ $index + 1 }}" 
                             class="w-full h-24 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"
                             loading="lazy">
                        <div class="absolute top-1 right-1">
                            <span class="bg-gray-500 text-white px-1 py-0.5 rounded text-xs font-semibold shadow-lg">
                                {{ $index + 1 }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(!$mainImageUrl && $galleryCount === 0)
        <div class="text-center py-8">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-500 text-sm">No images uploaded yet</p>
        </div>
    @endif
</div>
