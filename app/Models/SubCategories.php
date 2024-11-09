<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategories extends Model
{
    // Table name (if it doesn't follow the Laravel convention of pluralizing the model name)
    protected $table = 'tbl_subcategories';

    // Primary Key
    protected $primaryKey = 'sub_id';

    // Disable timestamps if not using 'created_at' and 'updated_at'
    public $timestamps = true;

    // If using custom timestamp columns, specify them
    const CREATED_AT = 'sub_created';
    const UPDATED_AT = 'sub_updated';

    // Attributes that are mass assignable
    protected $fillable = [
        'category_id',
        'sub_title',
        'sub_slug',
        'sub_image',
        'sub_description',
        'sub_colour_code',
        'sub_status',
    ];
    public function listings() {
        return $this->hasMany(Listings::class,'sub_category_id','sub_id');
    }
}
