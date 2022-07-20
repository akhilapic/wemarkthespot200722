<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferTypes extends Model
{
  use HasFactory;
      protected $table="offer_types";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name','category_id','short_information','created_at','updated_at','status'
    ];  
}
