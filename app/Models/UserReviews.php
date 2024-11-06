<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReviews extends Model
{
    // Define the table name if it doesn’t follow Laravel's naming convention
    protected $table = 'tbl_user_reviews';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'review_id';

    // Disable the default timestamps
    public $timestamps = true;

    // Specify fillable fields
    protected $fillable = [
        'listing_id',
        'review_user',
        'review_rating',
        'review_comment',
        'review_status',
    ];

    // Define the created_at and updated_at columns if they are not using the default names
    const CREATED_AT = 'review_created';
    const UPDATED_AT = 'review_updated';
}
