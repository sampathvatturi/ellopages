<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    const UPDATED_AT = 'categories_updated';
    protected $table = 'tbl_categories';

    protected $primaryKey = 'category_id';
    protected $fillable = [
        'category_title',
        'category_slug',
        'category_image',
        'category_description',
        'category_colour_code',
        'category_status',
    ];

    public $timestamps = true; // Ensure timestamps are enabled

    public function subcategories() {
        return $this->hasMany(SubCategories::class,'category_id','category_id');
    }
    public function listings() {
        return $this->hasMany(Listings::class,'category_id','category_id');
    }
}
