<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
     protected $fillable = [
        'name',
        'type',
        'price',
        'user_id',
        
    ];

    /*protected $hidden = [
        'created_at',
        'updated_at',
    ];*/
    
     public function plan_halls(){
        return $this->belongsToMany(PlanHall::class);
       }
       

}