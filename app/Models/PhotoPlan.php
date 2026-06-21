<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoPlan extends Model
{
    //
     protected $fillable = [
        'name',
        'descrption',
        'price',
       
        
    ];

    /*protected $hidden = [
        'created_at',
        'updated_at',
    ];*/
    
   public function bookings(){
        return $this->hasMany(Booking::class);
       }
        public function photographars(){
        return $this->belongsToMany(Photographar::class);
       }
       
       
}
