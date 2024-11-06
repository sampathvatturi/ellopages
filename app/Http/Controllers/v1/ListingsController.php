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
}
