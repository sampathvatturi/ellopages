<?php
namespace App\Repositories;

use App\Models\Categories;
use App\Models\SubCategories;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class CategoryRepository
{
    protected $category;
    protected $subCategory;

    public function __construct()
    {
        $this->category = new Categories();
        $this->subCategory = new SubCategories();
    }

    public function getAllCategories()
    {
        return $this->category->all();
    }
    public function getCategoryById($categoryId){
        return $this->category->find($categoryId);
    }
    public function getAllSubCategories()
    {
        return $this->subCategory->all();
    }
    public function getAllSubCategoriesByCategoryId($categoryId)
    {
        return $this->subCategory->where('category_id',$categoryId)->get();
    }
}