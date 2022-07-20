<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replies extends Model
{
  use HasFactory;
  protected $table = "replies";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id', 'review_id', 'reply_id', 'message', 'created_at', 'updated_at', 'type', 'image',
    'video_image_status', 'business_id', 'business_id2', 'business_id3', 'business_id4', 'business_id4','business_id5'
  ];

  public function user()
  {
    return $this->belongsTo('App\Models\User', 'user_id')->withDefault();
  }
  public function business()
  {
    return $this->belongsTo('App\Models\User', 'business_id')->withDefault();
  }
  public function business2()
  {
    return $this->belongsTo('App\Models\User', 'business_id2')->withDefault();
  }
  public function business3()
  {
    return $this->belongsTo('App\Models\User', 'business_id3')->withDefault();
  }
  public function business4()
  {
    return $this->belongsTo('App\Models\User', 'business_id4')->withDefault();
  }
  public function business5()
  {
    return $this->belongsTo('App\Models\User', 'business_id5')->withDefault();
  }
}
