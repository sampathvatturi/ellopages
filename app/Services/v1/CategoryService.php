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
}