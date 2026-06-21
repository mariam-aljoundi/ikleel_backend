<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    //
      protected $fillable = [
        'name',
        'location',
        'license',
        'user_id',
        
    ];

    /*protected $hidden = [
        'created_at',
        'updated_at',
    ];*/
    
     public function user(){
        return $this->belongsTo(User::class);
       }
        public function plan_halls(){
        return $this->hasMany(PlanHall::class);
       }

        public function media(){
        return $this->morphMany(Media::class, 'mediable');
       }

}
