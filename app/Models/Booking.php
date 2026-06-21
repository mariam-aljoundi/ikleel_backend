<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
     protected $fillable = [
        'event_date',
        'status',
        'total_price',
        'user_id',
        'service_id',
        'photographar_id'
    ];

    /*protected $hidden = [
        'created_at',
        'updated_at',
    ];*/

     public function user(){
        return $this->belongsTo(User::class);
       }
        public function plan_hall(){
        return $this->belongsTo(PlanHall::class);
       }
        public function plan_photo(){
        return $this->belongsTo( PhotoPlan::class);
       }
}
