<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Classifieds Peru') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('listings.index') }}" class="font-bold tracking-tight text-lg">Clasificados Peru</a>
            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('listings.index') }}" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50">Browse</a>
                @auth
                    <a href="{{ route('listings.create') }}" class="px-3 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700">Post Ad</a>
                    <a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50">Login</a>
                    <a href="{{ route('register') }}" class="px-3 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700">Register</a>
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

        <section class="bg-white border border-gray-200 rounded-xl p-4 md:p-6 mb-6">
            <form method="GET" action="{{ route('listings.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Title, city or keyword"
                        class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <select name="listing_type" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All types</option>
                        @foreach (['item' => 'Items', 'job' => 'Jobs', 'property' => 'Properties', 'vehicle' => 'Vehicles'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('listing_type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Province</label>
                    <select name="peru_province_id" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Any province</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province->id }}" @selected((string) request('peru_province_id') === (string) $province->id)>
                                {{ $province->province }} ({{ $province->department }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Filter</button>
                    <a href="{{ route('listings.index') }}" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">Reset</a>
                </div>
            </form>
        </section>

        @if ($listings->count() === 0)
            <div class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-gray-600">
                No ads found yet. Try another filter or publish the first ad.
            </div>
        @else
            <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach ($listings as $listing)
                    <article class="rounded-xl border border-gray-200 bg-white p-4">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <h2 class="font-semibold text-lg leading-tight">
                                <a href="{{ route('listings.show', $listing) }}" class="hover:underline">{{ $listing->title }}</a>
                            </h2>
                            <span class="text-xs uppercase px-2 py-1 rounded bg-gray-100 text-gray-700">{{ $listing->listing_type }}</span>
                        </div>

                        <p class="text-sm text-gray-600 line-clamp-3">{{ $listing->description }}</p>

                        <div class="mt-4 space-y-1 text-sm">
                            <p><span class="text-gray-500">Location:</span> {{ $listing->location_city }}{{ $listing->peruProvince ? ', '.$listing->peruProvince->province : '' }}</p>
                            <p><span class="text-gray-500">Price:</span> {{ $listing->price ? number_format((float) $listing->price, 2).' '.$listing->currency : 'Contact for price' }}</p>

                            @if ($listing->listing_type === 'job')
                                <p><span class="text-gray-500">Company:</span> {{ $listing->job_company ?: 'N/A' }}</p>
                            @endif

                            @if ($listing->listing_type === 'property')
                                <p><span class="text-gray-500">Property:</span> {{ ucfirst((string) $listing->property_listing_type) ?: 'N/A' }}</p>
                            @endif

                            @if ($listing->listing_type === 'vehicle')
                                <p><span class="text-gray-500">Vehicle:</span> {{ trim(($listing->vehicle_make ?: '').' '.($listing->vehicle_model ?: '')) ?: 'N/A' }}</p>
                            @endif
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-2">
                            <p class="text-xs text-gray-500">Posted by {{ $listing->user->name ?? 'Unknown' }} on {{ $listing->created_at?->format('d M Y') }}</p>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('listings.show', $listing) }}" class="text-xs px-2 py-1 rounded border border-gray-300 hover:bg-gray-50">View</a>
                                @auth
                                    @if (auth()->id() === $listing->user_id)
                                        <a href="{{ route('listings.edit', $listing) }}" class="text-xs px-2 py-1 rounded bg-blue-600 text-white hover:bg-blue-700">Edit</a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>

            <div class="mt-6">
                {{ $listings->links() }}
            </div>
        @endif
    </main>
</body>
</html>
