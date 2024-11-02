<?php
namespace App\Repositories;

use App\Models\Categories;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class CategoryRepository
{
    protected $category;

    public function __construct()
    {
        $this->category = new Categories();
    }

    public function getAllCategories()
    {
        return $this->category->all();
    }
}