@php
    $editing = isset($listing);
@endphp

@if ($errors->any())
    <div class="mb-6 rounded border border-red-200 bg-red-50 text-red-800 px-4 py-3">
        <p class="font-semibold mb-2">Please fix the following:</p>
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $action }}" class="space-y-6" id="listing-form" enctype="multipart/form-data">
    @csrf
    @if ($editing)
        @method('PATCH')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1" for="listing_type">Listing type</label>
            <select id="listing_type" name="listing_type" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                @foreach (['item' => 'Item', 'job' => 'Job', 'property' => 'Property', 'vehicle' => 'Vehicle'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('listing_type', $listing->listing_type ?? 'item') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="currency">Currency</label>
            <select id="currency" name="currency" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                <option value="PEN" @selected(old('currency', $listing->currency ?? 'PEN') === 'PEN')>PEN</option>
                <option value="USD" @selected(old('currency', $listing->currency ?? '') === 'USD')>USD</option>
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1" for="title">Title</label>
            <input id="title" name="title" type="text" value="{{ old('title', $listing->title ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1" for="description">Description</label>
            <textarea id="description" name="description" rows="5" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>{{ old('description', $listing->description ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="price">Price (optional)</label>
            <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $listing->price ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="location_city">City</label>
            <input id="location_city" name="location_city" type="text" value="{{ old('location_city', $listing->location_city ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="location_region">Region</label>
            <input id="location_region" name="location_region" type="text" value="{{ old('location_region', $listing->location_region ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="peru_province_id">Province</label>
            <select id="peru_province_id" name="peru_province_id" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Select province</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province->id }}" @selected((string) old('peru_province_id', $listing->peru_province_id ?? '') === (string) $province->id)>
                        {{ $province->province }} ({{ $province->department }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="contact_phone">Contact phone</label>
            <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', $listing->contact_phone ?? auth()->user()->phone) }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1" for="contact_email">Contact email</label>
            <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $listing->contact_email ?? auth()->user()->email) }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1" for="images">Upload photos (up to 10)</label>
            <input id="images" name="images[]" type="file" multiple accept="image/png,image/jpeg,image/webp"
                class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            <p class="text-xs text-gray-500 mt-1">JPG, PNG or WEBP. Max 4MB each.</p>
        </div>
    </div>

    <section id="job-fields" class="hidden border rounded-lg p-4 bg-gray-50">
        <h3 class="font-semibold mb-3">Job details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="job_company">Company</label>
                <input id="job_company" name="job_company" type="text" value="{{ old('job_company', $listing->job_company ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="job_employment_type">Employment type</label>
                <input id="job_employment_type" name="job_employment_type" type="text" placeholder="Full-time, part-time, contract" value="{{ old('job_employment_type', $listing->job_employment_type ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="job_salary_min">Salary min</label>
                <input id="job_salary_min" name="job_salary_min" type="number" step="0.01" min="0" value="{{ old('job_salary_min', $listing->job_salary_min ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="job_salary_max">Salary max</label>
                <input id="job_salary_max" name="job_salary_max" type="number" step="0.01" min="0" value="{{ old('job_salary_max', $listing->job_salary_max ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="md:col-span-2 flex items-center gap-2">
                <input id="job_is_remote" name="job_is_remote" type="checkbox" value="1" @checked(old('job_is_remote', $listing->job_is_remote ?? false)) class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="job_is_remote" class="text-sm">Remote friendly</label>
            </div>
        </div>
    </section>

    <section id="property-fields" class="hidden border rounded-lg p-4 bg-gray-50">
        <h3 class="font-semibold mb-3">Property details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="property_listing_type">Operation</label>
                <select id="property_listing_type" name="property_listing_type" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select</option>
                    <option value="rent" @selected(old('property_listing_type', $listing->property_listing_type ?? '') === 'rent')>Rent</option>
                    <option value="sale" @selected(old('property_listing_type', $listing->property_listing_type ?? '') === 'sale')>Sale</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="property_area_m2">Area (m2)</label>
                <input id="property_area_m2" name="property_area_m2" type="number" min="1" value="{{ old('property_area_m2', $listing->property_area_m2 ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="property_bedrooms">Bedrooms</label>
                <input id="property_bedrooms" name="property_bedrooms" type="number" min="0" value="{{ old('property_bedrooms', $listing->property_bedrooms ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="property_bathrooms">Bathrooms</label>
                <input id="property_bathrooms" name="property_bathrooms" type="number" min="0" value="{{ old('property_bathrooms', $listing->property_bathrooms ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>
    </section>

    <section id="vehicle-fields" class="hidden border rounded-lg p-4 bg-gray-50">
        <h3 class="font-semibold mb-3">Vehicle details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="vehicle_type">Vehicle type</label>
                <select id="vehicle_type" name="vehicle_type" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select</option>
                    <option value="car" @selected(old('vehicle_type', $listing->vehicle_type ?? '') === 'car')>Car</option>
                    <option value="motorcycle" @selected(old('vehicle_type', $listing->vehicle_type ?? '') === 'motorcycle')>Motorcycle</option>
                    <option value="truck" @selected(old('vehicle_type', $listing->vehicle_type ?? '') === 'truck')>Truck</option>
                    <option value="van" @selected(old('vehicle_type', $listing->vehicle_type ?? '') === 'van')>Van</option>
                    <option value="bus" @selected(old('vehicle_type', $listing->vehicle_type ?? '') === 'bus')>Bus</option>
                    <option value="other" @selected(old('vehicle_type', $listing->vehicle_type ?? '') === 'other')>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="vehicle_make">Brand</label>
                <input id="vehicle_make" name="vehicle_make" type="text" value="{{ old('vehicle_make', $listing->vehicle_make ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="vehicle_model">Model</label>
                <input id="vehicle_model" name="vehicle_model" type="text" value="{{ old('vehicle_model', $listing->vehicle_model ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="vehicle_year">Year</label>
                <input id="vehicle_year" name="vehicle_year" type="number" min="1950" max="2100" value="{{ old('vehicle_year', $listing->vehicle_year ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="vehicle_mileage_km">Mileage (km)</label>
                <input id="vehicle_mileage_km" name="vehicle_mileage_km" type="number" min="0" value="{{ old('vehicle_mileage_km', $listing->vehicle_mileage_km ?? '') }}" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="vehicle_transmission">Transmission</label>
                <select id="vehicle_transmission" name="vehicle_transmission" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select</option>
                    <option value="manual" @selected(old('vehicle_transmission', $listing->vehicle_transmission ?? '') === 'manual')>Manual</option>
                    <option value="automatic" @selected(old('vehicle_transmission', $listing->vehicle_transmission ?? '') === 'automatic')>Automatic</option>
                    <option value="cvt" @selected(old('vehicle_transmission', $listing->vehicle_transmission ?? '') === 'cvt')>CVT</option>
                    <option value="semi-automatic" @selected(old('vehicle_transmission', $listing->vehicle_transmission ?? '') === 'semi-automatic')>Semi-automatic</option>
                </select>
            </div>
        </div>
    </section>

    @if ($editing && $listing->images->isNotEmpty())
        <section class="border rounded-lg p-4 bg-gray-50">
            <h3 class="font-semibold mb-3">Current photos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ($listing->images->sortBy('sort_order') as $image)
                    <div class="border border-gray-200 rounded p-3 bg-white">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}" alt="Listing image" class="w-full h-40 object-cover rounded mb-3">
                        <div class="flex items-center gap-4 text-sm">
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="primary_image_id" value="{{ $image->id }}"
                                    @checked(old('primary_image_id', $listing->images->firstWhere('is_primary', true)?->id) == $image->id)>
                                Primary
                            </label>
                            <label class="inline-flex items-center gap-2 text-red-700">
                                <input type="checkbox" name="remove_image_ids[]" value="{{ $image->id }}"
                                    @checked(collect(old('remove_image_ids', []))->contains((string) $image->id))>
                                Remove
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <div class="flex items-center gap-3">
        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">{{ $submitLabel }}</button>
        <a href="{{ $cancelRoute }}" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">Cancel</a>
    </div>
</form>

<script>
    (function () {
        const typeInput = document.getElementById('listing_type');
        const job = document.getElementById('job-fields');
        const property = document.getElementById('property-fields');
        const vehicle = document.getElementById('vehicle-fields');

        const toggleSections = () => {
            const value = typeInput.value;
            job.classList.toggle('hidden', value !== 'job');
            property.classList.toggle('hidden', value !== 'property');
            vehicle.classList.toggle('hidden', value !== 'vehicle');
        };

        typeInput.addEventListener('change', toggleSections);
        toggleSections();
    })();
</script>
