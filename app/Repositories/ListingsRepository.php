<?php
namespace App\Repositories;

use App\Models\Categories;
use App\Models\ListingImages;
use App\Models\Listings;
use App\Models\ListingTimings;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class ListingsRepository
{
    protected $listings,$listingsImages,$listingTimings;

    public function __construct()
    {
        $this->listings = new Listings();
        $this->listingsImages = new ListingImages();
        $this->listingTimings = new ListingTimings();
    }

    public function getAllListings(){
        return $this->listings->get();
    }

    public function getListingById($id){
        return $this->listings->first($id);
    }

    public function getListingsByCategory($categoryId){
        return $this->listings->where('category_id', $categoryId)->get();
    }
    public function getListingsBySubCategory($subCategoryId){
        return $this->listings->where('sub_category_id', $subCategoryId)->get();
    }
}