<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $listing->title }} | {{ config('app.name', 'Classifieds Peru') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('listings.index') }}" class="font-bold tracking-tight text-lg">Clasificados Peru</a>
            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('listings.index') }}" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50">Browse</a>
                @auth
                    <a href="{{ route('listings.mine') }}" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50">My Ads</a>
                    <a href="{{ route('listings.create') }}" class="px-3 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700">Post Ad</a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50">Login</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('status'))
            <div class="mb-6 rounded border border-green-200 bg-green-50 text-green-800 px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
                <div>
                    <h1 class="text-2xl font-semibold">{{ $listing->title }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Posted by {{ $listing->user->name ?? 'Unknown' }} on {{ $listing->created_at?->format('d M Y') }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs uppercase tracking-wide text-gray-700">{{ $listing->listing_type }}</span>
                    <p class="mt-2 text-xl font-bold">{{ $listing->price ? number_format((float) $listing->price, 2).' '.$listing->currency : 'Contact for price' }}</p>
                </div>
            </div>

            @if ($listing->images->isNotEmpty())
                <section class="mb-6">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($listing->images->firstWhere('is_primary', true)?->path ?? $listing->images->first()->path) }}"
                        alt="Main image"
                        class="w-full max-h-[420px] object-cover rounded-lg border border-gray-200 mb-3">
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        @foreach ($listing->images as $image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}" alt="Listing image"
                                class="w-full h-24 object-cover rounded border border-gray-200">
                        @endforeach
                    </div>
                </section>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2">
                    <h2 class="font-semibold mb-2">Description</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $listing->description }}</p>
                </div>

                <aside class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <h2 class="font-semibold mb-3">Details</h2>
                    <div class="text-sm space-y-2">
                        <p><span class="text-gray-500">City:</span> {{ $listing->location_city }}</p>
                        <p><span class="text-gray-500">Region:</span> {{ $listing->location_region ?: 'N/A' }}</p>
                        <p><span class="text-gray-500">Province:</span> {{ $listing->peruProvince?->province ?: 'N/A' }}</p>
                        <p><span class="text-gray-500">Phone:</span> {{ $listing->contact_phone }}</p>
                        <p><span class="text-gray-500">Email:</span> {{ $listing->contact_email }}</p>

                        @if ($listing->listing_type === 'job')
                            <hr class="my-2 border-gray-200">
                            <p><span class="text-gray-500">Company:</span> {{ $listing->job_company ?: 'N/A' }}</p>
                            <p><span class="text-gray-500">Type:</span> {{ $listing->job_employment_type ?: 'N/A' }}</p>
                            <p><span class="text-gray-500">Remote:</span> {{ $listing->job_is_remote ? 'Yes' : 'No' }}</p>
                        @endif

                        @if ($listing->listing_type === 'property')
                            <hr class="my-2 border-gray-200">
                            <p><span class="text-gray-500">Operation:</span> {{ $listing->property_listing_type ?: 'N/A' }}</p>
                            <p><span class="text-gray-500">Bedrooms:</span> {{ $listing->property_bedrooms ?? 'N/A' }}</p>
                            <p><span class="text-gray-500">Bathrooms:</span> {{ $listing->property_bathrooms ?? 'N/A' }}</p>
                            <p><span class="text-gray-500">Area:</span> {{ $listing->property_area_m2 ? $listing->property_area_m2.' m2' : 'N/A' }}</p>
                        @endif

                        @if ($listing->listing_type === 'vehicle')
                            <hr class="my-2 border-gray-200">
                            <p><span class="text-gray-500">Type:</span> {{ $listing->vehicle_type ?: 'N/A' }}</p>
                            <p><span class="text-gray-500">Brand:</span> {{ $listing->vehicle_make ?: 'N/A' }}</p>
                            <p><span class="text-gray-500">Model:</span> {{ $listing->vehicle_model ?: 'N/A' }}</p>
                            <p><span class="text-gray-500">Year:</span> {{ $listing->vehicle_year ?: 'N/A' }}</p>
                            <p><span class="text-gray-500">Mileage:</span> {{ $listing->vehicle_mileage_km ? number_format((int) $listing->vehicle_mileage_km).' km' : 'N/A' }}</p>
                        @endif
                    </div>
                </aside>
            </div>

            @auth
                @if (auth()->id() === $listing->user_id)
                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="{{ route('listings.edit', $listing) }}" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Edit</a>
                        <a href="{{ route('listings.mine') }}" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">My Ads</a>
                        <form method="POST" action="{{ route('listings.destroy', $listing) }}" onsubmit="return confirm('Delete this ad permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </main>
</body>
</html>
