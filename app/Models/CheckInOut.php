<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckInOut extends Model
{
  use HasFactory;
      protected $table="checkInout";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',  'business_id','status','created_at','updated_at', 'type', 'check_status'
    ];

    function business(){
          return  $this->belongsTo('App\Models\User', 'business_id')->withDefault();
      }


}
