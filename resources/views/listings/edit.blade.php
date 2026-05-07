<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Ad</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @include('listings._form', [
                        'listing' => $listing,
                        'action' => route('listings.update', $listing),
                        'submitLabel' => 'Save Changes',
                        'cancelRoute' => route('listings.show', $listing),
                    ])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
