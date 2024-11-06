<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingImages extends Model
{
     // Define the table name if it doesn’t follow Laravel's naming convention
     protected $table = 'tbl_listing_images';

     // Define the primary key if it's not 'id'
     protected $primaryKey = 'image_id';
 
     // Disable the default timestamps
     public $timestamps = true;
 
     // Specify fillable fields
     protected $fillable = [
         'listing_id',
         'image_source',
     ];
 
     // Define the created_at and updated_at columns if they are not using the default names
     const CREATED_AT = 'image_created';
     const UPDATED_AT = 'image_updated';
}
