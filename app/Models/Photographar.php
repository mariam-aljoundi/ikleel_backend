<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photographar extends Model
{
    //
     protected $fillable = [
        'name',
        'availabiltay',
       
        
    ];

    /*protected $hidden = [
        'created_at',
        'updated_at',
    ];*/
    
     public function plan_photos(){
        return $this->belongsToMany(PhotoPlan::class);
       }
       
}
