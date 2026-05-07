<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListingRequest;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\PeruProvince;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::query()
            ->with(['user:id,name', 'peruProvince:id,department,province'])
            ->where('status', 'active')
            ->latest();

        if ($request->filled('q')) {
            $search = trim((string) $request->string('q'));
            $query->where(function ($inner) use ($search) {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location_city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('listing_type')) {
            $query->where('listing_type', $request->string('listing_type'));
        }

        if ($request->filled('peru_province_id')) {
            $query->where('peru_province_id', $request->integer('peru_province_id'));
        }

        return view('listings.index', [
            'listings' => $query->paginate(12)->withQueryString(),
            'provinces' => PeruProvince::query()
                ->orderBy('department')
                ->orderBy('province')
                ->get(['id', 'department', 'province']),
        ]);
    }

    public function show(Listing $listing)
    {
        $listing->load([
            'user:id,name',
            'peruProvince:id,department,province',
            'images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'),
        ]);

        if ($listing->status !== 'active') {
            $userId = auth()->id();

            if (! $userId || $listing->user_id !== $userId) {
                abort(404);
            }
        }

        return view('listings.show', ['listing' => $listing]);
    }

    public function create()
    {
        return view('listings.create', [
            'provinces' => PeruProvince::query()
                ->orderBy('department')
                ->orderBy('province')
                ->get(['id', 'department', 'province']),
        ]);
    }

    public function store(StoreListingRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['images']);

        $data['user_id'] = $request->user()->id;
        $data['status'] = 'active';
        $data['published_at'] = now();

        $listing = Listing::create($this->normalizeByType($data));

        $this->storeUploadedImages($listing, $request);

        return redirect()
            ->route('listings.show', $listing)
            ->with('status', "Ad '{$listing->title}' published successfully.");
    }

    public function mine(Request $request)
    {
        return view('listings.my', [
            'listings' => Listing::query()
                ->with('images')
                ->where('user_id', $request->user()->id)
                ->latest()
                ->paginate(12),
        ]);
    }

    public function edit(Listing $listing)
    {
        $this->ensureOwner($listing);

        $listing->load('images');

        return view('listings.edit', [
            'listing' => $listing,
            'provinces' => PeruProvince::query()
                ->orderBy('department')
                ->orderBy('province')
                ->get(['id', 'department', 'province']),
        ]);
    }

    public function update(StoreListingRequest $request, Listing $listing): RedirectResponse
    {
        $this->ensureOwner($listing);

        $data = $request->validated();
        unset($data['images'], $data['remove_image_ids'], $data['primary_image_id']);

        $listing->update($this->normalizeByType($data));

        $this->removeSelectedImages($listing, $request);
        $this->storeUploadedImages($listing, $request);
        $this->setPrimaryImage($listing, $request);

        return redirect()
            ->route('listings.show', $listing)
            ->with('status', 'Ad updated successfully.');
    }

    public function destroy(Listing $listing): RedirectResponse
    {
        $this->ensureOwner($listing);

        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $listing->delete();

        return redirect()
            ->route('listings.mine')
            ->with('status', 'Ad deleted successfully.');
    }

    private function normalizeByType(array $data): array
    {
        $type = $data['listing_type'] ?? 'item';

        if ($type !== 'job') {
            $data['job_company'] = null;
            $data['job_employment_type'] = null;
            $data['job_salary_min'] = null;
            $data['job_salary_max'] = null;
            $data['job_is_remote'] = false;
        }

        if ($type !== 'property') {
            $data['property_listing_type'] = null;
            $data['property_bedrooms'] = null;
            $data['property_bathrooms'] = null;
            $data['property_area_m2'] = null;
        }

        if ($type !== 'vehicle') {
            $data['vehicle_type'] = null;
            $data['vehicle_make'] = null;
            $data['vehicle_model'] = null;
            $data['vehicle_year'] = null;
            $data['vehicle_mileage_km'] = null;
            $data['vehicle_transmission'] = null;
        }

        return $data;
    }

    private function ensureOwner(Listing $listing): void
    {
        abort_unless(auth()->id() === $listing->user_id, 403);
    }

    private function storeUploadedImages(Listing $listing, StoreListingRequest $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $sortOrder = (int) $listing->images()->max('sort_order');
        $hasPrimary = $listing->images()->where('is_primary', true)->exists();

        foreach ($request->file('images') as $i => $file) {
            $sortOrder++;

            $path = $file->store('listings', 'public');

            ListingImage::create([
                'listing_id' => $listing->id,
                'path' => $path,
                'sort_order' => $sortOrder,
                'is_primary' => ! $hasPrimary && $i === 0,
            ]);
        }
    }

    private function removeSelectedImages(Listing $listing, StoreListingRequest $request): void
    {
        $ids = collect($request->input('remove_image_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter();

        if ($ids->isEmpty()) {
            return;
        }

        $images = $listing->images()->whereIn('id', $ids->all())->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }

    private function setPrimaryImage(Listing $listing, StoreListingRequest $request): void
    {
        $primaryId = (int) $request->integer('primary_image_id');

        if ($primaryId <= 0) {
            return;
        }

        $image = $listing->images()->where('id', $primaryId)->first();

        if (! $image) {
            return;
        }

        $listing->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
    }
}
