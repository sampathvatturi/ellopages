<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'tbl_users'; // Specify your table name

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'u_reference_id',
        'u_name',
        'u_email',
        'u_pcode',
        'u_phone',
        'u_password',
        'u_encrypted_password',
        'u_profile_picture',
        'u_otp',
        'u_otp_timestamp',
        'u_referred_by',
        'u_lat',
        'u_long',
        'u_ip',
        'u_device',
        'u_device_token',
        'u_status',
        'u_joined', // if you are manually inserting timestamps
        'u_updated'
    ];

    // Hidden attributes for arrays
    protected $hidden = [
        'u_password', 'u_encrypted_password', 'u_otp'
    ];

    // By default, Laravel uses `id` as the primary key
    // In your case, it seems `u_id` is the primary key
    protected $primaryKey = 'u_id';
    
    public $timestamps = false;

    // Hash the password before saving to the database
    public function setPasswordAttribute($value)
    {
        $this->attributes['u_encrypted_password'] = bcrypt($value);
    }

    public function setUPasswordAttribute($value)
    {
        // Encrypt the password before saving it in u_encrypted_password
        $this->attributes['u_encrypted_password'] = bcrypt($value);
    }

}
