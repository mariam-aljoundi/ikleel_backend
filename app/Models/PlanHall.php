<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanHall extends Model
{
    //
      protected $fillable = [
        'name',
        'descrption',
        'price',
        'capcity',
        
    ];

    /*protected $hidden = [
        'created_at',
        'updated_at',
    ];*/
    
     public function hall(){
        return $this->belongsTo(Hall::class);
       }
        public function bookings(){
        return $this->hasMany(Booking::class);
       }
       
        public function services(){
        return $this->belongsToMany(Service::class);
       }
}
