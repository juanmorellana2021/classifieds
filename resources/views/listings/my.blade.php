<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Ads</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded border border-green-200 bg-green-50 text-green-800 px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('listings.create') }}" class="inline-flex items-center px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Post New Ad</a>
            </div>

            @if ($listings->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-gray-600 text-center">You have not posted any ads yet.</div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach ($listings as $listing)
                        <article class="bg-white border border-gray-200 rounded-xl p-4">
                            @php
                                $thumb = $listing->images->firstWhere('is_primary', true)?->path ?? $listing->images->first()?->path;
                            @endphp

                            @if ($thumb)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($thumb) }}" alt="Listing image" class="w-full h-40 object-cover rounded mb-3">
                            @endif

                            <h3 class="font-semibold text-lg leading-tight">{{ $listing->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ ucfirst($listing->listing_type) }} · {{ ucfirst($listing->status) }}</p>
                            <p class="text-sm text-gray-700 mt-2">{{ \Illuminate\Support\Str::limit($listing->description, 110) }}</p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('listings.show', $listing) }}" class="px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50 text-sm">View</a>
                                <a href="{{ route('listings.edit', $listing) }}" class="px-3 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700 text-sm">Edit</a>
                                <form method="POST" action="{{ route('listings.destroy', $listing) }}" onsubmit="return confirm('Delete this ad permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded bg-red-600 text-white hover:bg-red-700 text-sm">Delete</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $listings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
