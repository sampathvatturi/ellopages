<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingTimings extends Model
{
    // Define the table name if it doesn’t follow Laravel's naming convention
    protected $table = 'tbl_listing_timings';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'timing_id';

    // Disable the default timestamps
    public $timestamps = true;

    // Specify fillable fields
    protected $fillable = [
        'listing_id',
        'timing_day_of_week',
        'timing_opening_time',
        'timing_closing_time',
        'timing_is_24_hours',
    ];

    // Define the created_at and updated_at columns if they are not using the default names
    const CREATED_AT = 'timing_created';
    const UPDATED_AT = 'timing_updated';
}
