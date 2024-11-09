<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\v1\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller
{
    protected $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }
    public function getCategories(){
        $response = [];
        $categories = $this->categoryService->getAllCategories();
        if(count($categories) == 0){
            return response()->json($response);
        };
        foreach($categories as $category){
            $response[] = [
                'id' => $category->category_id,
                'label' => $category->category_title,
                'imagePath' => $category->category_image,
                'color' => $category->category_colour_code,
            ];
        }
        return response()->json($response);
    }

    public function getSubCategories(Request $request){
        try{
            $response = [];
            $request->validate([
                'categoryId' =>'required|integer',
            ]);
            $categoryId = $request->categoryId;
            $subCategoriesWithCategory = $this->categoryService->getAllSubCategoriesByCategoryIdWithCategory($categoryId);
            if(count(array($subCategoriesWithCategory)) == 0){
                return response()->json($response);
            };
            $response['category']['id'] = $subCategoriesWithCategory->category->category_id;
            $response['category']['label'] = $subCategoriesWithCategory->category->category_title;
            $response['category']['description'] = $subCategoriesWithCategory->category->category_description;
            $subCategories = $subCategoriesWithCategory->subcategories;
            foreach($subCategories as $subCategory){
                $response['category']['subCategories'][] = [
                    'id' => $subCategory->sub_id,
                    'category_id' => $subCategory->category_id,
                    'label' => $subCategory->sub_title,
                    'imagePath' => $subCategory->sub_image,
                    'color' => $subCategory->sub_colour_code,
                ];
            }
            return response()->json($response);
        }catch(\Exception $exception){
            Log::info($exception);
            return response()->json([
               'success' => false,
               'message' => 'An error occurred while fetching subcategories',
                'error_type' => 'general_error'
            ], 500);
        }
    }
}
