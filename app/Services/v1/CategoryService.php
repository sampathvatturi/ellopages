<?php

namespace App\Services\v1;

use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Hash;

class CategoryService
{
    protected $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->getAllCategories();
    }

    public function getAllSubCategories()
    {
        return $this->categoryRepository->getAllSubCategories();
    }
    public function getAllSubCategoriesByCategoryIdWithCategory($categoryId)
    {
        $data = new \stdClass();
        $category = $this->categoryRepository->getCategoryById($categoryId);
        if($category){
            $data->category = $category;
            $data->subcategories = $category->subcategories;
        }
        return $data;
    }
}