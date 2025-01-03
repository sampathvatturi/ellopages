<?php

namespace App\Services\v1;

use App\Repositories\CategoryRepository;
use App\Repositories\ListingsRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ListingsService
{
    protected $listingRepository;

    public function __construct()
    {
        $this->listingRepository = new ListingsRepository();
    }

    public function getListingsByCategory($categoryId)
    {
        $data = [];
        $listings = $this->listingRepository->getListingsByCategory($categoryId);
        foreach ($listings as $listing){
            $data[] = [
                'id' => $listing->listing_id,
                'title' => $listing->listing_name,
                'imageUrl' => $listing->images[0]->image_source ?? '',
                // 'timings' => $listing->timings[0]->timing_opening_time ?? ''.' to '.$listing->timings[0]->timing_closing_time ?? '',
                'timings' => '',
                'address' => $listing->listing_address1,
                'isFavorite' => false,
                'mobile' => $listing->listing_phone,
                'email' => $listing->listing_email,
            ];
        }
        return $data;
    }
    public function getListingsBySubCategory($subCategoryId)
    {
        $data = [];
        $listings = $this->listingRepository->getListingsByCategory($subCategoryId);
        foreach ($listings as $listing){
            $data[] = [
                'data' => $listing
            ];
            // $data[] = [
            //     'id' => $listing->listing_id,
            //     'title' => $listing->listing_name,
            //     'imageUrl' => $listing->images[0]->image_source ?? '',
            //     // 'timings' => $listing->timings[0]->timing_opening_time ?? ''.' to '.$listing->timings[0]->timing_closing_time ?? '',
            //     'timings' => '',
            //     'address' => $listing->listing_address1,
            //     'isFavorite' => false,
            //     'mobile' => $listing->listing_phone,
            //     'email' => $listing->listing_email,
            // ];
        }
        return $data;
    }

    public function addListing($data)
    {
        // Ensure any null values are converted to empty strings
        $data = array_map(function($value) {
            return $value === null ? '' : $value;
        }, $data);

        // Add the authenticated user's ID to the data
        $data['user_id'] = Auth::id();

        return $this->listingRepository->createListing($data);
    }
}