<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listings extends Model
{
    // Define the table name if it doesn’t follow Laravel's naming convention
    protected $table = 'tbl_listings';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'listing_id';

    // Disable the default timestamps
    public $timestamps = true;

    // Specify fillable fields
    protected $fillable = [
        'category_id',
        'listing_name',
        'listing_slug',
        'listing_description',
        'listing_featured',
        'listing_email',
        'listing_pcode',
        'listing_phone',
        'listing_profile_picture',
        'listing_country',
        'listing_state',
        'listing_city',
        'listing_pincode',
        'listing_address1',
        'listing_address2',
        'listing_lat',
        'listing_long',
        'listing_googlemap_link',
        'listing_website_link',
        'listing_social_links',
        'listing_ip',
        'listing_device',
        'listing_device_token',
        'listing_seo_title',
        'listing_seo_keywords',
        'listing_seo_description',
        'listing_admin_status',
        'listing_status',
    ];

    // Define the created_at and updated_at columns if they are not using the default names
    const CREATED_AT = 'listing_created';
    const UPDATED_AT = 'listing_updated';

    protected $with = ['images', 'timings', 'reviews'];
    public function images(){
        return $this->hasMany(ListingImages::class,'listing_id','listing_id');
    }

    public function timings(){
        return $this->hasMany(ListingTimings::class,'listing_id','listing_id');
    }

    public function reviews(){
        return $this->hasMany(UserReviews::class,'listing_id','listing_id');
    }
}
