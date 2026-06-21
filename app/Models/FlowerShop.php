<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlowerShop extends Model
{
    //
    protected $fillable = [
        'name',
        'location',
        'license',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
       }

    public function media(){
        return $this->morphMany(Media::class, 'mediable');
       }
}
