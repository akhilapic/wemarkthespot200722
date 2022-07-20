<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
  use HasFactory;
      protected $table="offers";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','user_id','offer_name','category_id','offer_type','deactivation','offer_message','activation','created_at','updated_at','status'
    ];  
}
