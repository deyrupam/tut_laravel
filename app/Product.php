<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'detail'
    ];
// Accessors
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
  

   
}
