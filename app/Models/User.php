<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'username', 'email', 'country_code', 'status', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at',
    //     'role', 'image', 'phone', 'gender', 'dob', 'specialization', 'upload_doc', 'address', 'education',
    //     'bio', 'language', 'login_check', 'business_type', 'email_verified', 'location', 'opeing_hour', 'closing_hour', 'description',
    //     'business_name', 'business_category', 'approved', 'reason', 'business_sub_category', 'business_images', 'lat', 'long', 'ratting','religious_spiritual',
    //     'device_token','current_promotion','notification_status'
    // ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function category()
    {
       return $this->belongsTo('App\Models\Categorys', 'business_category')->withDefault();
    }
    function subcategory()
    {
        return  $this->belongsTo('App\Models\SubCategorys', 'business_sub_category')->withDefault();
    }
}
