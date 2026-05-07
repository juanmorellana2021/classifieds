<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'listing_type' => ['required', 'in:item,job,property,vehicle'],
            'title' => ['required', 'string', 'min:8', 'max:180'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'in:PEN,USD'],
            'location_city' => ['required', 'string', 'max:120'],
            'location_region' => ['nullable', 'string', 'max:120'],
            'peru_province_id' => ['nullable', 'exists:peru_provinces,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'contact_email' => ['required', 'email', 'max:255'],

            'job_company' => ['required_if:listing_type,job', 'nullable', 'string', 'max:180'],
            'job_employment_type' => ['required_if:listing_type,job', 'nullable', 'string', 'max:60'],
            'job_salary_min' => ['nullable', 'numeric', 'min:0'],
            'job_salary_max' => ['nullable', 'numeric', 'min:0', 'gte:job_salary_min'],
            'job_is_remote' => ['nullable', 'boolean'],

            'property_listing_type' => ['required_if:listing_type,property', 'nullable', 'in:rent,sale'],
            'property_bedrooms' => ['nullable', 'integer', 'min:0', 'max:50'],
            'property_bathrooms' => ['nullable', 'integer', 'min:0', 'max:50'],
            'property_area_m2' => ['nullable', 'integer', 'min:1', 'max:10000000'],

            'vehicle_type' => ['required_if:listing_type,vehicle', 'nullable', 'in:car,motorcycle,truck,van,bus,other'],
            'vehicle_make' => ['required_if:listing_type,vehicle', 'nullable', 'string', 'max:80'],
            'vehicle_model' => ['required_if:listing_type,vehicle', 'nullable', 'string', 'max:80'],
            'vehicle_year' => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'vehicle_mileage_km' => ['nullable', 'integer', 'min:0', 'max:5000000'],
            'vehicle_transmission' => ['nullable', 'in:manual,automatic,cvt,semi-automatic'],

            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image_ids' => ['nullable', 'array', 'max:10'],
            'remove_image_ids.*' => ['integer'],
            'primary_image_id' => ['nullable', 'integer'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'job_is_remote' => $this->boolean('job_is_remote'),
        ]);
    }
}
