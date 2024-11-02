<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\v1\CategoryService;
use Illuminate\Http\Request;

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
}
