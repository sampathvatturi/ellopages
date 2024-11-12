<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\v1\ListingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ListingsController extends Controller
{
    protected $listingService;

    public function __construct()
    {
        $this->listingService = new ListingsService();
    }
    public function getListingsByCategory(Request $request){
        try{
            $request->validate([
                'categoryId' =>'required|integer',
            ]);

            $categoryId = $request->categoryId;
    
            $response = [];
            $listings = $this->listingService->getListingsByCategory($categoryId);
            
            return response()->json($listings);
        }catch (\Exception $exception){
            Log::info($exception);
            return response()->json([
               'success' => false,
               'message' => $exception->getMessage(),
                'error_type' => 'general_error'
            ], 500);
        }
    }
    public function getListingsBySubCategory(Request $request){
        try{
            $request->validate([
                'subCategoryId' =>'required|integer',
            ]);

            $subCategoryId = $request->subCategoryId;
    
            $response = [];
            $listings = $this->listingService->getListingsBySubCategory($subCategoryId);
            
            return response()->json($listings);
        }catch (\Exception $exception){
            Log::info($exception);
            return response()->json([
               'success' => false,
               'message' => $exception->getMessage(),
                'error_type' => 'general_error'
            ], 500);
        }
    }

    public function addListing(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required|integer',
                'sub_category_id' => 'required|integer',
                'listing_name' => 'required|string',
                'listing_slug' => 'nullable|string',
                'listing_description' => 'required|string',
                'listing_featured' => 'nullable|boolean',
                'listing_email' => 'required|email',
                'listing_pcode' => 'required|string',
                'listing_phone' => 'required|string',
                'listing_profile_picture' => 'nullable|string',
                'listing_country' => 'required|string',
                'listing_state' => 'required|string',
                'listing_city' => 'required|string',
                'listing_pincode' => 'required|string',
                'listing_address1' => 'required|string',
                'listing_address2' => 'nullable|string',
                'listing_lat' => 'required|string',
                'listing_long' => 'required|string',
                'listing_googlemap_link' => 'nullable|string',
                'listing_website_link' => 'nullable|string',
                'listing_social_links' => 'nullable|string',
                'listing_ip' => 'nullable|string',
                'listing_device' => 'nullable|string',
                'listing_device_token' => 'nullable|string',
                'listing_seo_title' => 'nullable|string',
                'listing_seo_keywords' => 'nullable|string',
                'listing_seo_description' => 'nullable|string',
                'images' => 'nullable|string',
                'timings' => 'required|string',
            ]);

            $data = $request->all();
            $listing = $this->listingService->addListing($data);

            return response()->json($listing, 201);
        } catch (\Exception $exception) {
            Log::info($exception);
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'error_type' => 'general_error'
            ], 500);
        }
    }
}
